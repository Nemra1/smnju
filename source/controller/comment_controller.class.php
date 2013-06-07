<?php

define('NO_PERMISSION', '您没有执行此操作的权限哦~');
define('SUCCESS', '操作成功啦~');

$comment_types = array('topic', 'article', 'discussion');

class CommentController extends Controller
{

    protected static $require_admin = array();
    protected static $require_log_in = array();
    protected static $require_visitor = array();
    protected static $require_id = array();
    protected static $me_or_other = array();
    protected static $pag=array();

    static function init()
    {
        self::$require_log_in = array('delete', 'create');
        self::$require_id = array('delete', 'conversation', 'get_siblings', 'comment_tree','get_children');
    }

    //POST中要有commented_id,content||||或者是subject_id,content,comment_type
    public function create()
    {
        //comment的create应该是通过post方法提交的
        if (is_post()) {
            //根据post里的数据创建并且设置comment对象
            $comment = Comment::create($_POST);
            $res = $comment->insert();
            //如果插入不成功，将消息显示给用户
            if ($res === false) {
                server_response('评论创建失败.' . $comment->error_msg);
            }
            //将新建的评论整理成html形式返回给浏览器，浏览器根据返回的内容，会显示该新建的评论
            $comment->interval_time = get_interval_time(time() - $comment->create_time);
            $comment->user = User::load($comment->user_id);
            $array = array('comment' => $comment);
            $comment_item_html = fetch_smarty($array, 'comment/comment_tree_node.tpl');
            echo $comment_item_html;
        }
    }

    /**
     * 要删除评论需要是管理员,或者评论是该用户发布的
     * 一般是因为点击了删除comment的叉叉,然后会指向这个action
     *
     * 需要的数据:
     * $c: 表示要删除的comment的id
     *
     * NOTE:commentDelete需要页面提交后自己把该comment隐藏,本函数不会刷新页面
     *
     */
    function delete()
    {
        global $c;
        $comment = Comment::load($c);
        //判断是否可以删除
        if ($comment === false) {
            server_response(-1, "不存在该评论");
        }
        if ($comment->user_id != $_SESSION['user_id'] && !is_admin()) {
            server_response(-1, '您没有删除该评论的权限');
        }
        if ($comment->is_delete == 'yes') {
            server_response(-1, "该评论已被删除,不能再次删除");
        }
        //填入删除者的信息
        $comment->is_delete = 'yes';
        $comment->who_delete = $_SESSION['user_id'];
        $comment->which_role_delete = $comment->user_id == $_SESSION['user_id'] ? 'user' : 'admin';
        $res = $comment->update();
        //反馈删除信息
        if ($res === false) {
            server_response(-1, "因为内部原因导致评论删除失败");
        } else {
            server_response(1, '评论被您成功删除');
        }
    }

    /**
     * 根据brother的id和排序方法,获取接下来的几条评论
     * 需要的数据
     * $c表示 last_comment_id
     */
    function get_siblings()
    {
        global $c;
        //获取某条评论的兄弟评论
        $comment_list = Comment::get_comment_sibling_list($c, array(8, 0));
        $array = array('comment_list' => $comment_list);
        $comment_html = fetch_smarty($array, 'comment/comment_tree_sibling_nodes.tpl');
        echo $comment_html;
    }

    /**
     * 根据comment的id获取评论该comment的子评论
     */
    function get_children(){
        global $c;
        $comment=Comment::load($c);
        if($comment===false){
            server_response(-1,'不存在该评论');
        }
        $comment_list=$comment->get_sub_comment_tree();
        $array=array('comment_list'=>$comment_list);
        $comment_html=fetch_smarty($array,'comment/comment_tree_sibling_nodes.tpl');
        echo $comment_html;
    }

    /**
     * 返回评论树，从某条评论开始，向树根回溯的评论的会话结构(链表结构)
     */
    public function conversation()
    {
        //$c表示从哪条评论开始回溯，$d表示回溯的长度
        global $c, $d;
        //如果d没有设置，则默认为10
        isset($d) ? : $d = 10;
        $comment_id = $c;
        $comment_list = array();

        for ($i = 0; $i < $d; $i++) {
            $comment = Comment::load($comment_id);
            if ($comment === false) {
                break;
            }
            $user = User::load($comment->user_id);
            $comment->user = $user;
            $comment_list[] = $comment;
            $comment_id = $comment->commented_id;
        }
        $comment_list = array_reverse($comment_list);
        $array = array('comment_list' => $comment_list);
        //如果是ajax请求, 则不要用register_smarty把除对话内容以外的东西放进去
        if (in_ajax()) {
            $content = fetch_smarty($array, "comment/conversation.tpl");
            echo $content;
        } else {
            register_smarty($array);
        }
    }

    /**
     * 返回某主题的评论的树形结构
     */
    function comment_tree()
    {
        global $c;
        $comment_tree = Comment::get_comment_list_tree($c,$_GET['comment_type']);
        $array=array('comment_tree' => $comment_tree);
        if (in_ajax()) {
            $comment_tree_html = fetch_smarty($array, 'comment/comment_tree.tpl');
            echo $comment_tree_html;
        } else {
            register_smarty($array);
        }
    }

}

CommentController::init();

?>