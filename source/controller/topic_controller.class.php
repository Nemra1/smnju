<?php

class TopicController extends Controller
{
	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();

	static function init()
	{
		self::$require_admin = array('create', 'edit', 'admin', 'delete','restore');
		self::$require_id = array('edit', 'delete', 'show','restore');
		self::$pag=array('index_pag','discussion_of_topic_pag');
	}

	function index()
	{
		$topics_content = $this->index_pag_html(1);
		register_smarty(array('topics_content' => $topics_content));
	}


	public function index_pag()
	{
		$ret=$this->index_pag_html($_GET['page_num']);
		echo $ret;
	}

	private function index_pag_html($cur_page){
		$pagination=new Pagination(10,$cur_page,5,site_url('/topic/index_pag'),'#topic_list');
		$topics=$pagination->fetch_objects('Topic',array('is_delete'=>'no'),'create_time desc');
		$topics=Model::snormalize($topics);
		$html=$pagination->generate_html('topic/index_pag.tpl',array('topics'=>$topics));
		return $html;
	}

	function admin()
	{
		$array=array();
		if(isset($_GET['type'])&&$_GET['type']=='delete_status')
		$array['is_delete']='yes';
		else
		$array['is_delete']='no';

		$topics = Topic::get_objects($array);
		$topics=Model::snormalize($topics);
		register_smarty(array('topics' => $topics));
	}

	public function create()
	{
		if (is_post()) {
			$array=$_POST;
			$array['user_id']=$_SESSION['user_id'];
			$topic = Topic::create($array);
			$ret = $topic->insert();
			if ($ret === false) {
				echo_object_error($topic, '创建失败');
			}
			else {
				server_response(1, $ret);
			}
		}
		register_smarty();
	}

	public function edit()
	{
		global $c;
		if (is_post()) {
			$topic = Topic::load($c);

			$ret = $topic->update($_POST);
			if ($ret === false) {
				echo_object_error($topic, '修改失败');
			} else {
				server_response(1);
			}
		}
		$topic = Topic::load($c);

		if ($topic === false)
		show_message('url错误');

		$topic->normalize();


		register_smarty(array('topic' => $topic));
	}


	public function delete()
	{
		global $c;
		if (is_post()) {
			$topic = Topic::load($c);
			$ret = $topic->delete();
			server_response(1);
		}
	}


	function restore(){
		global $c;
		if(is_post()){
			$topic=Topic::load($c);
			$topic->restore();
			server_response(1);

		}
	}

	/**
	 * 根据url中的show的id,显示话题,包括topic内容,不包括comment列表
	 */
	public function show()
	{
		global $c;
		$topic = Topic::load($c);
		$topic->increase_read_count();

		$topic->normalize();
		$discussion_content = $this->discussion_of_topic_pag_html($c,1);
		$array = array('topic' => $topic, 'discussion_list_content' => $discussion_content);
		register_smarty($array);
	}

	function discussion_of_topic_pag(){
		global $c;
		$content=$this->discussion_of_topic_pag_html($c,$_GET['page_num']);
		echo $content;

	}

	private function discussion_of_topic_pag_html($c,$d){
		$pagination=new Pagination(10,$d,5,site_url("/topic/$c/discussion_of_topic_pag/"),'#discussion_list');

		$discussions=$pagination->fetch_objects('Discussion',array('topic_id'=>$c,'is_delete'=>'no'),'create_time asc');
		$discussions=Model::snormalize($discussions);

		$html=$pagination->generate_html('topic/discussion_of_topic_pag.tpl',array('discussions'=>$discussions));
		return $html;
	}

}

TopicController::init();


?>