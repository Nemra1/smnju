<?php
class ActivityAnnouncementController extends Controller{

	protected static $require_admin=array();
	protected  static $require_log_in=array();
	protected  static $require_visitor=array();
	protected  static $require_id=array();
	protected static $me_or_other=array();
	protected static $pag=array();



	static function init(){
		self::$require_admin=array('create','edit','admin','delete','restore');
		self::$require_id=array('edit','delete','show','restore');
		self::$pag=array('index_pag');
	}

	function admin(){
		$array=array();
		if(isset($_GET['type'])&&$_GET['type']=='delete_status')
		$array['is_delete']='yes';
		else
		$array['is_delete']='no';
		
		$aas=ActivityAnnouncement::get_objects($array);
		$aas=Model::snormalize($aas);
		foreach($aas as $k=>$v){
			$activity_id=$v->activity_id;
			if($activity_id!==null){
				$activity=Activity::load($activity_id);
				$aas[$k]->activity_name=$activity->name;
			}
		}
		register_smarty(array('activity_announcements'=>$aas));
	}

	function create(){
		if(is_post()){
			if($_POST['activity_id']==-1){
				unset($_POST['activity_id']);
			}
			$aa=ActivityAnnouncement::create($_POST);
			$ret=$aa->insert();
			if($ret===false){
				echo_object_error($aa,'创建失败');
			}
			else {
				server_response(1,$ret);
			}
		}
		$activities=Activity::get_objects(array('is_delete'=>'no'));
		$activity_options=array();
		foreach($activities as $a){
			$activity_options[$a->id]=$a->name;
		}
		register_smarty(array('activity_options'=>$activity_options));
	}

	function edit(){
		global $c;
		if(is_post()){
			$aa=ActivityAnnouncement::load($c);

			$aa->merge_attrs($_POST);
			$ret=$aa->update();
			if($ret===false){
				echo_object_error($aa,'修改失败');
			}else{
				server_response(1);
			}
		}
		$activity_announcement=ActivityAnnouncement::load($c);
		if($activity_announcement===false)
		show_message('url','错误');

		$activities=Activity::get_objects(array('is_delete'=>'no'));
		$activities_options=array();
		foreach($activities as $ga){
			$activities_options[$ga->id]=$ga->name;
		}
		register_smarty(array('activity_announcement'=>$activity_announcement,'activities_options'=>$activities_options));
	}

	function show(){
		global $c;

		$aa=ActivityAnnouncement::load($c);

		if($aa===false){
			show_message('url错误');
		}

		$aa->normalize();



		if($aa->activity_id!==null){
			$activity=Activity::load($aa->activity_id);
			register_smarty(array('activity_announcement'=>$aa,'activity'=>$activity));
		}
		register_smarty(array('activity_announcement'=>$aa));
	}

	function index(){
		$ret=  $this->index_pag_html(1);
		register_smarty(array('aa_list_content'=>$ret));
	}

	function index_pag(){
		if(is_post()){
			$ret=$this->index_pag_html($_GET['page_num']);
			echo $ret;
		}
	}

	private function index_pag_html($cur_page){

		$pagination=new Pagination(10,$cur_page,5,site_url('/activity_announcement/index_pag'),'#activity_announcement_list');

		$aa_list=$pagination->fetch_objects('ActivityAnnouncement',0,'create_time desc');
		$aa_list=Model::snormalize($aa_list);

		$html=$pagination->generate_html('activity_announcement/index_pag.tpl',array('aa_list'=>$aa_list));
		return $html;

	}





	function delete(){
		global $c;
		if(is_post()){
			$aa=ActivityAnnouncement::load($c);

			$ret=$aa->delete();

			server_response(1);
		}
	}

	function restore(){
		global $c;
		if(is_post()){
			$aa=ActivityAnnouncement::load($c);

			$aa->restore();
			server_response(1);

		}
	}



}
ActivityAnnouncementController::init();
?>