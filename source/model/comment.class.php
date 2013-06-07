<?php
/**
 * Author:Lubo
 * Time:2011.10.15
 * Desc:
 * 该类是评论，对应于数据库中的comment表
 * comment能表示所有在comment_type （mysql中的enum类型）中定义的类型的评论
 */
include_once S_ROOT . "cometchat/cometchat_init.php";

class Comment extends Model
{
    public static $table_name = 'comment';
    public static $class_name = 'Comment';
    public static $id_name = 'id';

    protected static $translate = array();
    protected static $require_not_null = array();
    protected static $unique = array();
    protected static $unchanged = array();
    protected static $reg_validate = array();

    /**
     * @static
     * 初始化静态变量
     */
    static function init()
    {
        self::$require_not_null = array('subject_id','comment_type','commented_id', 'content', 'user_id');
    }

    private $commented_comment = null;

    /**
     * 设置插入评论需要满足的其他条件如：
     * user_id
     * create_time
     * has_new_comment
     * has_been_read
     * floor_count
     * commented_id
     * subject_id
     */
    protected function pre_insert()
    {
        //如果subject_id没有设置,要根据commented_id找到comment,然后再找到subject_id
        if (array_key_exists('subject_id', $this->attrs)&&
            empty($this->attrs['commented_id'])) {
            $this->commented_id = 0;
            $this->floor_count = 1;
        } else if (!empty($this->attrs['commented_id'])) {
            $this->commented_comment = Comment::load($this->commented_id);
            $this->subject_id = $this->commented_comment->subject_id;
            $this->floor_count = $this->commented_comment->floor_count + 1;
            $this->comment_type=$this->commented_comment->comment_type;
        }
        $this->user_id = $_SESSION['user_id'];
        $this->create_time = time();
        $this->has_new_comment = 'no';
        $this->has_been_read = 'yes';
    }

    /**
     * 1.把被评论的comment的有新评论改成‘是’，新评论已阅读改成‘否’
     * 2.该被评论的subject下的总评论数加一
     * 3.将评论事件以提醒的形式通知被评论用户
     */
    protected function post_insert()
    {
        global $_SC;
        //如果在pre_insert中已经载入了commented_count，则修改它因本comment的插入而应当修改的内容
        if (isset($this->commented_comment)) {
            $this->commented_comment->has_new_comment = 'yes';
            $this->commented_comment->has_been_read = 'no';
            $this->commented_comment->sub_comment_count++;
            $this->commented_comment->update();
        }
        //将该评论针对的主题的评论数加1
        $subject_class = ucfirst($this->comment_type);
        $subject = null;
        $subject=$subject_class::load($this->subject_id);
        $subject->increase_comment_count();

        //将评论的被评论的消息通知给被评论的评论的创建者
        //检查是否回复给了某条评论，因为还有可能是回复给了主题，另外如果是给自己回复，则不发出通知
        if (isset($this->commented_comment)&&$this->commented_comment->user_id!=$this->user_id) {
            sendAnnouncement($this->commented_comment->user_id, $_SESSION['user_name'] . "评论了你,<a href='$_SC[siteurl]/comment/conversation/$this->id' target='_blank'>点击查看</a>");
        }
    }

    /**
     * 如果is_delete被设为yes，则把内容改成“该评论被。。。删除”
     * 加入创建时间的字符串表示
     * 加入评论被创建到载入该评论所间隔的时间
     */
    protected function post_load()
    {
        if ($this->is_delete == 'yes') {
            if ($this->which_role_delete == 'admin') {
                $this->content = "该评论被管理员删除";
            } else {
                $this->content = "该评论被用户自己删除";
            }
        }
        $this->create_time_str = get_formated_time($this->create_time);
        $this->interval_time = get_interval_time(time() - $this->create_time);
    }

    /**
     * 如果comment对象直接从数组创建，则也要调用post_load，使对象内容变得完整
     */
    protected function post_create()
    {
        if (!isset($this->create_time)) {
            $this->create_time = time();
        }
        $this->post_load();
    }

    /**
     * 对某条评论来讲，从它开始，有更多的兄弟评论，这个方法就是用来获取这些信息
     * @static
     * @param $comment_id 相对哪条评论开始
     * @return mixed 相对于所给评论，还有几条更多评论
     */
    public static function more_comment_count($comment_id)
    {
        $comment = Comment::load($comment_id);
        $more_comment_count = get_element('comment', "count(*) as c", "subject_id='$comment->subject_id' and comment_type='$comment->comment_type' and commented_id='$comment->commented_id' and id<'$comment_id'");
        return $more_comment_count[0]['c'];
    }

    /**
     * 获取某评论的子评论的树形数组，并且把该数组赋值给该对象的sub_comment_list属性
     * @param $node_num 子树每层的节点个数
     * @return mixed
     */
    public function get_sub_comment_tree($node_num = array(10, 0))
    {
        $node_in_this_layer = array_shift($node_num);
        if (empty($node_in_this_layer)) {
            return;
        }
        $comment_array = get_element('comment', "*", "subject_id='$this->subject_id' and commented_id='$this->id'", "id DESC", $node_in_this_layer);

        $this->sub_comment_list = self::trans_comment_array_to_objects($comment_array, $node_num);
        return $this->sub_comment_list;
    }

    /**
     * 获取某个主题的评论的树形结构
     * @static
     * @param $subject_id comment评论主题的id
     * @param $comment_type
     * @param array $node_num 评论的树形层次深度和每层评论的多少
     * @return array 评论的树形数组
     */
    public static function get_comment_list_tree($subject_id,$comment_type, $node_num = array(10, 4, 3, 0))
    {
        $current_layer_node_num = array_shift($node_num);
        if ($current_layer_node_num <= 0) {
            return;
        }
        $comment_array = get_element('comment', "*", "subject_id='$subject_id' and comment_type='$comment_type' and commented_id='0'", "id DESC", $current_layer_node_num);
        return self::trans_comment_array_to_objects($comment_array, $node_num);
    }

    /**
     * 获取某条评论的同一层次的几条评论及其树形的向下层的节点
     * @static
     * @param $comment_id 指定某条评论
     * @param array $node_num 同一层次的评论及其树形层次节点的深度及每层取的评论数、
     * @return array 获取评论的对象数组，树形层次的数组
     */
    public static function get_comment_sibling_list($comment_id, $node_num = array(5, 4, 3, 0))
    {
        $comment = Comment::load($comment_id);
        $current_layer_node_num = array_shift($node_num);
        $comment_array = get_element('comment', "*", "subject_id='$comment->subject_id' and commented_id='$comment->commented_id' and comment_type='$comment->comment_type' and id<'$comment->id'", "id DESC", $current_layer_node_num);
        $comment_list = array();
        foreach ($comment_array as $value) {
            $comment = Comment::create($value);
            $comment->user = User::load($comment->user_id);
            $comment->get_sub_comment_tree($node_num);
            $comment_list[] = $comment;
        }
        return $comment_list;
    }

    /**
     * 将评论的数组转为评论的对象
     * @static
     * @param $comment_array 评论的数组
     * @param $sub_tree_node_num 去数组中每条评论的子评论树形结构
     * @return array
     */
    private static function trans_comment_array_to_objects($comment_array, $sub_tree_node_num)
    {
        $comment_object_list = array();
        foreach ($comment_array as $value) {
            $comment = Comment::create($value);
            $comment->user = User::load($comment->user_id);
            $comment->get_sub_comment_tree($sub_tree_node_num);
            $comment_object_list[] = $comment;
        }
        return $comment_object_list;
    }

}

Comment::init();

function smarty_more_comment_count($comment_id)
{
    return Comment::more_comment_count($comment_id);
}