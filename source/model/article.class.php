<?php
/**
 * This file is generate from table:
 * TABLE_NAME: article
 * TABLE_COMMENT:
 */

class Article extends Model implements CommentSubject
{

    public static $table_name = 'article';
    public static $class_name = 'Article';
    public static $id_name = 'id';
    protected static $translate = array('id' => 'id', 'topic_id' => 'topic_id', 'user_id' => 'user_id', 'title' => 'title', 'create_time' => 'create_time', 'participate_contribution' => 'participate_contribution', 'content' => 'content');
    protected static $require_not_null = array('topic_id', 'user_id', 'title', 'content');
    protected static $unique = array();
    protected static $unchanged = array();
    protected static $reg_validate = array();
    protected static $s_IsOwner = NULL;
    

    static function init()
    {
        self::add_reg_validate('title', '/^\w{1,255}$/', '标题过长');
        self::$require_not_null = array('title', 'content', 'topic_id');
    }

    function normalize()
    {
        $this->content = str_replace("\n", "<br/>", $this->content);
    }


    private $needInsertIntoContribution = false;

    /**
     * 选择将本文投稿
     */
    public function contribute()
    {
        if ($this->hasContributed()) {
            return;
        }
        //如果已经插到article表中,并且article_contribute中还没插入,则把article的id插入article_contribute中即可
        if ($this->has_id() && !$this->hasContributed()) {
            inserttable('article_contribute', array('article_id' => $this->get_id()));
        } else if (!$this->has_id()) {
            $this->needInsertIntoContribution = true;
        }
    }

    public function create_action($id)
    {
        if ($this->needInsertIntoContribution) {
            inserttable('article_contribute', array('article_id' => $id));
            $this->needInsertIntoContribution = false;
        }
    }

    public function hasContributed()
    {
        if ($this->has_id()) {
            return check_if_exist('article_contribute', array('article_id' => $this->get('id')));
        } else {
            return false;
        }
    }

    public static function article_count($topic_id)
    {
        $article_count = get_element('article', "count(*) as count", "topic_id='$topic_id'");
        return $article_count[0]['count'];
    }

    public static function limit_content($content)
    {
        $portion_content = utf_substr($content, 300);
        $portion_content .= (strlen($portion_content) < strlen($content)) ? '......' : '';
        return $portion_content;
    }

    public static function add_support_vote($article_id, $user_id)
    {
        //判断是否存在该主题
        //XXX 这个步骤是可以舍去的
        $article = Article::load($article_id);
        if ($article === false) {
            return false;
        }
        //判断是否已经投票
        $res = get_element('article_vote', "count(*) as count", "article_id='$article_id' and user_id='$user_id'");
        if ($res[0]['count'] > 0) {
            return false;
        }
        //如果没有投过票，则把票加入article_vote，并且把article记录中的票数加一
        inserttable('article_vote', array('article_id' => $article_id, 'user_id' => $user_id));
        updatetable('article', "`vote_number`=`vote_number`+1", array('id' => $article_id));
        $res = get_element_by_key('article', 'id', "$article_id", 'vote_number');
        return $res['vote_number'];
    }


    public static function is_owner($article_id, $user_id)
    {
        if (self::$s_IsOwner) {
            return self::$s_IsOwner;
        }
        $res = get_element('article', " count(*) AS count", "id='$article_id' AND user_id='$user_id'");
        self::$s_IsOwner = ($res[0]['count'] > 0) ? true : false;
        return self::$s_IsOwner;
    }

    /**
     * 获取Article对象后，要把时间戳改为字符串表示的时间
     */
    public function post_load()
    {
        $this->create_time = get_formated_time($this->create_time);
        $this->due_time = get_formated_time($this->due_time);
    }

    /**
     * 1.将被创建文章所属的主题的文章数加1
     * 2.文章是由用户创建，文章创建之后需要把创建文章的新鲜事插入到用户新鲜事表中
     *
     */
    protected function post_insert()
    {
        $topic = Topic::load($this->topic_id);
        $topic->increase_article_count();

        $user_news = News::create();

        $user_news->news_type =News::$news_type['create_article'];
        $user_news->news_role=News::$news_role['user'];

        $array=array();
        $array['topic_id']=$topic->id;
        $array['topic_name']=$topic->name;

        $array['article_title']=$this->title;
        $array['article_id']=$this->id;
        $array['article_content_brief']=utf_substr($this->content,116);

        $user_news->tpl_array_json=json_encode($array);

        $user_news->insert();
    }

    /**
     * 增加评论数
     */
    public function increase_comment_count(){
        $this->comment_count++;
        $this->update();
    }
}

Article::init();
?>