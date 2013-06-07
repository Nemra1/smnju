<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lubo
 * Date: 02/12/11
 * Time: 12:50
 */

class DiscussionController extends Controller
{
	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();

	static function init()
	{
		self::$require_log_in = array('edit', 'delete', 'create');
		self::$require_id = array('edit', 'show', 'delete', "all_of_user", "all_of_topic",'restore');
	}

	function index()
	{
		register_smarty(array());
	}

	function admin()
	{
		global $c;
		$array=array();
		if(isset($_GET['type'])&&$_GET['type']=='delete_status')
		$array['is_delete']='yes';
		else
		$array['is_delete']='no';

		$topic=Topic::load($c);
		if($topic===false)
		show_message('url错误');

		$array['topic_id']=$c;
		$discussions = Discussion::get_objects($array);
		$discussions=Model::snormalize($discussions);
		register_smarty(array('discussions' => $discussions,'topic'=>$topic));
	}

	function show()
	{
		global $c;

		$discussion = Discussion::load($c);
		if ($discussion === false) {
			show_message("您指定的讨论不存在");
		}
		$array['discussion'] = $discussion;
		$array['topic'] = Topic::load($discussion->topic_id);
		if(logged_in())
		$array['is_owner'] = $_SESSION['user_id'] == $discussion->user_id ? 'yes' : 'no';
		else
		$array['is_owner']=false;

		register_smarty($array);
	}

	function edit()
	{
		global $c;

		if (is_post()) {
			$discussion = Discussion::load($c);
			if ($discussion === false) {
				server_response('-1', '您要修改的讨论不存在或者已经删除');
			}

			if ($discussion->user_id != $_SESSION['user_id']) {
				server_response('-1', "没有修改的权限");
			}
			$discussion->update($_POST);
			server_response(1, "$discussion->id");
		}

		$discussion = Discussion::load($c);
		if ($discussion === false) {
			show_message("您要修改的讨论不存在或者已经删除");
		}
		if ($discussion->user_id != $_SESSION['user_id']) {
			show_message("没有修改的权限");
		}
		$topic = Topic::load($discussion->topic_id);
		if ($topic === false) {
			show_message("不存在该主题");
		}
		$array['topic'] = $topic;
		$array["discussion"] = $discussion;
		register_smarty($array);
	}

	function delete()
	{
		global $c;
		if(is_post()){
			$discussion = Discussion::load($c);

			if ($discussion->user_id != $_SESSION['user_id'] && !is_admin()) {
				server_response(-1, '您没有权限删除该帖子');
			}

			$discussion->delete();
			server_response(1);
		}

	}


	function restore()
	{
		global $c;
		if(is_post()){
			$discussion = Discussion::load($c);
			if ($discussion === false) {
				server_response("-1", "该讨论不存在");
			}

			if ($discussion->user_id != $_SESSION['user_id'] && !is_admin()) {
				server_response("-1", '您没有权限操作该帖子');
			}

			$discussion->restore();
			server_response(1);
		}

	}

	/**
	 * 在Get里提供topic id
	 * 在Post里提供discussion的具体内容
	 */
	function create()
	{
		if (is_post()) {
			$discussion = $_POST;
			$discussion['user_id'] = $_SESSION['user_id'];
			$discussion = Discussion::create($discussion);
			$ret = $discussion->insert();
			if ($ret === false) {
				echo_object_error($discussion, '讨论创建失败');
			}
			server_response(1, $ret);
		}
		//如果不是提交的,则显示编辑框等
		$topic_id = $_GET['topic_id'];
		$topic = Topic::load($topic_id);
		if ($topic === false) {
			show_message("不存在该主题");
		}
		$array['topic'] = $topic;
		register_smarty($array);
	}

	function all_of_topic()
	{
		global $c;
		echo get_discussion_page_html($_GET['page_no'], "topic_id='$c'", "id DESC", "/discussion/all_of_user", 15);
	}

	function all()
	{
		echo get_discussion_page_html($_GET['page_no'], "1=1", "id DESC", "/discussion/all", 15);
	}

	function all_of_user()
	{
		global $c;
		echo get_discussion_page_html($_GET['page_no'], "user_id='$c'", "id DESC", "/discussion/all_of_user", 15);
	}
}

DiscussionController::init();
?>