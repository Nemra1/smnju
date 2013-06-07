<?php
class SiteAnnouncementController extends Controller{
	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();

	static function init(){
		self::$require_admin=array('create','admin','delete','edit','restore');
		self::$require_id=array('edit','show','delete','restore');
		self::$pag=array('index_pag');
	}



	function index(){
		$sa_content=$this->index_pag_html(1);
		register_smarty(array('sa_list_content'=>$sa_content,'is_index_page'=>true));
	}

	function index_pag(){
		$ret=$this->index_pag_html($_GET['page_num']);
		echo $ret;
	}


	private function index_pag_html($cur_page){
		$pagination=new Pagination(10,$cur_page,5,site_url('/site_announcement/index_pag'),'#site_announcement_list');

		$sa_list=$pagination->fetch_objects('SiteAnnouncement',0,'create_time desc');
		$sa_list=Model::snormalize($sa_list);


		$html=$pagination->generate_html('site_announcement/index_pag.tpl',array('sas'=>$sa_list));
		return $html;
	}

	function show(){
		global $c;
		$sa=SiteAnnouncement::load($c);
		if($sa===false)
		show_message('公告不存在');

		$sa->normalize();
		register_smarty(array('sa'=>$sa));
	}

	function create(){
		if(is_post()){
			$sa=$_POST;
			$sa_object=SiteAnnouncement::create($sa);
			$ret=$sa_object->insert();
			if($ret===false){
				echo_object_error($sa_object,'创建错误');
			}
			server_response(1,$ret);
		}
		register_smarty();
	}



	function edit(){
		global $c;
		if(is_post()){
			$sa=SiteAnnouncement::load($c);
			if($sa===false)
			server_response(-1,'公告不存在！');

			$ret=$sa->update($_POST);
			if($ret===false)
			echo_object_error($sa,'更新失败！');

			server_response(1,$c);
		}

		$sa=SiteAnnouncement::load($c);
		register_smarty(array('sa'=>$sa));
	}

	function admin(){
		$array=array();
		if(isset($_GET['type'])&&$_GET['type']=='delete_status')
		$array['is_delete']='yes';
		else
		$array['is_delete']='no';

		$sas=SiteAnnouncement::get_objects($array);
		$sas=Model::snormalize($sas);
		register_smarty(array('sas'=>$sas));
	}

	function delete(){
		global $c;
		if(is_post()){
			$sa=SiteAnnouncement::load($c);
			if($sa===false){
				echo_object_error($sa,'公告不存在');
			}
			$sa->delete();
			server_response(1);
		}
	}

	function restore(){
		global $c;
		if(is_post()){
			$sa=SiteAnnouncement::load($c);
			$sa->restore();
			server_response(1);

		}
	}


}

SiteAnnouncementController::init();
?>