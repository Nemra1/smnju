<?php
class ActivityController extends Controller
{


	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();


	static function init()
	{
		self::$require_admin = array('create', 'admin', 'delete','edit','restore');
		self::$require_id = array('edit', 'show', 'delete','restore');
		self::$pag=array('aa_pag','all_pag');
	}

	function index()
	{
		$activities = Activity::get_objects(array('is_delete'=>'no'));
		$aa_content=$this->aa_pag_html(1);
		register_smarty(array('activities' => $activities, 'aa_list_content' => $aa_content));
	}

	function aa_pag(){

		$aa_content=$this->aa_pag_html($_GET['page_num']);
		echo $aa_content;
	}

	private function aa_pag_html($cur_page){
		$pagination=new Pagination(10,$cur_page,5,site_url('/activity/aa_pag'),'#activity_announcement_list');

		$aa_list=$pagination->fetch_objects('ActivityAnnouncement',0,'create_time desc');
		$aa_list=Model::snormalize($aa_list);


		$html=$pagination->generate_html('activity/aa_pag.tpl',array('aa_list'=>$aa_list));
		return $html;
	}


	function show()
	{
		global $c;
		$activity = Activity::load($c);
		if ($activity === false)
		show_message('url错误');
		$activity->normalize();
		$activity_announcements = ActivityAnnouncement::get_objects(array('activity_id' => $c,'is_delete'=>'no'));
		$activity_announcements=Model::snormalize($activity_announcements);
		register_smarty(array('activity' => $activity, 'activity_announcements' => $activity_announcements));
	}

	function create()
	{
		if (is_post()) {
			$activity = $_POST;
			$activity_object = Activity::create($activity);
			$ret = $activity_object->insert();
			if ($ret === false) {
				echo_object_error($activity_object, '创建错误');
			}
			server_response(1, $ret);
		}
		register_smarty();
	}


	function edit()
	{
		global $c;
		if (is_post()) {
			$activity = Activity::load($c);

			$activity->merge_attrs($_POST);
			$ret = $activity->update();
			if ($ret === false)
			echo_object_error($activity, '更新失败！');

			server_response(1, $c);
		}
		$activity = Activity::load($c);
		register_smarty(array('activity' => $activity));
	}

	function admin()
	{
		$array=array();
		if(isset($_GET['type'])&&$_GET['type']=='delete_status')
		$array['is_delete']='yes';
		else
		$array['is_delete']='no';

		$activities = Activity::get_objects($array);
		$activities=Model::snormalize($activities);
		register_smarty(array('activities' => $activities));
	}

	function delete()
	{
		global $c;
		if (is_post()) {
			$activity = Activity::load($c);
			$activity->delete();
			server_response(1);
		}
	}

	function restore(){
		global $c;
		if(is_post()){
			$activity=Activity::load($c);

			$activity->restore();
			server_response(1);

		}
	}

	function all(){
		$ret=$this->all_pag_html(1);
		register_smarty(array('activity_list_content'=>$ret));
	}

	function all_pag(){


		$ret=$this->all_pag_html($_GET['page_num']);
		echo $ret;
	}

	private function all_pag_html($cur_page){

		$pagination=new Pagination(10,$cur_page,5,site_url('/activity/all_pag'),'#activity_list');

		$activities=$pagination->fetch_objects('Activity',0,'create_time asc');
		$activities=Model::snormalize($activities);


		$html=$pagination->generate_html('activity/all_pag.tpl',array('activities'=>$activities));
		return $html;
	}




}

ActivityController::init();

?>