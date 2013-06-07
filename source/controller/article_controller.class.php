<?php

//NOTICE delete 的真正状态是delete置位
class ArticleController extends Controller
{


    protected static $require_admin = array();
    protected static $require_log_in = array();
    protected static $require_visitor = array();
    protected static $require_id = array();
    protected static $me_or_other = array();
    protected static $pag=array();


    static function init()
    {
        self::$require_log_in = array('edit', 'create', 'delete');
        self::$require_id = array('edit', 'show', 'delete', 'all_of_user', 'all_of_topic');
    }

    private $topic;

    //TODO
    private function hasArticleBeenScored($article)
    {

    }

    private function hasArticlePassedDueTime($article)
    {
        global $topic;
        $topic = Topic::load($article->topic_id);
        return time() > strtotime($topic->due_time);
    }


    private function hasTopicPassedDueTime($topic)
    {
        write_log(strtotime($topic->due_time));
        return time() > strtotime($topic->due_time);
    }


    public function index()
    {
        register_smarty(array());
    }

    /**
     * 如果是更新文章，需要判断该文章是否是该用户的
     * 当文章已经参加投稿，则不能编辑
     * $c表示topic_id
     * $d表示article_id
     */
    function edit()
    {
        global $c;

        $article = Article::load($c);
        if ($article === false) {
            server_response(-1, '您要修改的文章不存在');
        }

        if ($_SESSION['user_id'] != $article->user_id) {
            server_response(-1, '您不是文章的创建者，没有编辑该文章的权限');
        }

        if ($this->hasArticlePassedDueTime($article)) {
            server_response(-1, '您的这篇文章已经参加投稿并且已经超过该主题投稿的截止时间，不能再编辑');
        }

        if (is_post()) {
            $article->update($_POST);
            server_response(1, $article->id);
        }

        $topic = Topic::load($article->topic_id);
        $array['topic'] = $topic;
        $array['article'] = $article;
        register_smarty($array);
    }

    /**
     * 如果是用户，并且在post中有article的内容，则插入
     * 输出：
     * code:
     * 1表示成功
     * 0表示失败
     */
    function create()
    {
        global $c;

        if (is_post()) {
            $article = $_POST;
            $article['user_id'] = $_SESSION['user_id'];
            $article = Article::create($article);
            $ret = $article->insert();
            if ($ret === false) {
                echo_object_error($article, '创建失败');
            }

            server_response(1, $ret);

        }

        $topic = Topic::load($c);
        if ($this->hasTopicPassedDueTime($topic))
            show_message('主题征文截止了');
        else
            register_smarty(array('topic' => $topic));
    }


    /**
     * 当文章已经参加投稿，则不能删除
     * 文章的创建者和管理员可以删除文章
     */
    function delete()
    {
        global $c;

        $article = Article::load($c);

        if ($article === false) {
            server_response(-1, "您要删除的文章不存在");
        }
        //如果是创建文章用户本人删除文章，则要检查是否已经参加投稿并且超期了
        if ($article->user_id != $_SESSION['user_id'] && !is_admin()) {
            //如果文章已经参加投稿并且已经过了投稿时间，则不能删除
            server_response(-1, '您不是该文章的创建者也不是管理员，不能删除该文章');
        }

        if ($this->hasArticlePassedDueTime($article)) {
            server_response(-1, "文章已经投稿并且已经超出投稿截止日期，不能删除");
        }

        $article->who_delete = $_SESSION['user_id'];
        $article->delete_time = time();
        $article->is_delete = 'yes';
        $article->which_role_delete = is_admin() ? 'admin' : 'user';
        $article->update();
        server_response(1);

    }


    function show()
    {
        global $c, $topic;

        $article = Article::load($c);
        if ($article === false) {
            server_response(-1, '没有指定的文章');
        }
        $hasScored = $this->hasArticleBeenScored($article);
        $array['has_passed_due_time'] = $this->hasArticlePassedDueTime($article) ? 1 : 0;
        $array['has_been_scored'] = $hasScored === false ? 'no' : 'yes';
        $array['article'] = $article;
        $array['topic'] = $topic;
        $array['is_owner'] = $article->user_id == $_SESSION['user_id'] || is_admin() ? 1 : 0;
        register_smarty($array);
    }

    function admin()
    {
        $articles = Article::get_objects();
        Model::snormalize($articles);
        foreach ($articles as $k => $v) {
            $topic_id = $v->topic_id;
            if ($topic_id !== null) {
                $topic = Topic::load($topic_id);
                $articles[$k]->topic_name = $topic->name;
            }
        }
        register_smarty(array('articles' => $articles));
    }


    function score()
    {
        global $c;
        if (is_post()) {
            $article = Article::load($c);
            if ($article === false) {
                server_response(-1, '无此征文');
            }

            $article->score = $c;
            $article->update();
            server_response(1, '修改成功');
        }

        $article = Article::load($c);
        $article->normalize();
        if ($c === false) {
            show_message('无此征文');
        }

        $topic = Topic::load($article->topic_id);

        register_smarty(array('article' => $article, 'topic' => $topic));
    }

    function list_all()
    {
        global $c;
        $topic_id = 0;
        $array = array();
        if (isset($c)) {
            $topic_id = $c;
        } elseif (exist_in_post('topic_id')) {
            $topic_id = $_POST['topic_id'];
        } else {
            echo "{code:0,info:没有指定文章所属的主题}";
            exit();
        }
        $topic = Topic::load($topic_id);
        if ($topic === false) {
            echo "{code:0,info:指定的主题不存在}";
            exit();
        }
        $articles = Article::get_objects("topic_id='$topic_id'");
        /**
         * 截取一段文字
         */
        foreach ($articles as &$value) {
            $value->portion_content = Article::limit_content($value->content);
        }
        $array['topic'] = $topic;
        $array['articles'] = $articles;
        $array['article_count'] = Article::article_count($topic_id);

        register_smarty($array);
    }

    //投票表示对该文章的支持
    /**
     * code:
     * 0    不是注册用户，没有权限
     * 1    没有指定文章
     * 2    重复投票
     * 4    投票成功
     */
    public function support_vote()
    {
        global $c;
        //先判断用户权限
        if (!logged_in()) {
            echo "{code:0,info:'不是注册用户,没有对文章投票的权限'}";
            exit();
        }
        //采集变量
        $article_id = (isset($c)) ? $c : (exist_in_post('article_id') ? $_POST['article_id'] : false);
        if ($article_id === false) {
            echo "{code:1,info:'不能对该文章投票'}";
            exit();
        }
        $res = Article::add_support_vote($article_id, $_SESSION['user_id']);
        if ($res === false) {
            echo "{code:2,info:'不能对文章重复投票'}";
        } else {
            echo "{code:4,info:'$res'}";
        }
    }

    public function all_of_user()
    {
        global $c;

        echo get_article_page_html($_GET['page_no'], "user_id='$c'", "id DESC", "/article/all_of_user", 15);
    }

    public function all()
    {
        echo get_article_page_html($_GET['page_no'], "1=1", "id DESC", "/article/all", 15);
    }

    public function all_of_topic()
    {
        global $c;

        echo get_article_page_html($_GET['page_no'], "topic_id='$c'", "id DESC", "/article/all_of_topic", 15);
    }

}

ArticleController::init();
?>
