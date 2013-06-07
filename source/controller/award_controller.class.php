<?php
class AwardController extends Controller{

	protected static $require_admin = array();
	protected static $require_log_in = array();
	protected static $require_visitor = array();
	protected static $require_id = array();
	protected static $me_or_other = array();
	protected static $pag=array();


	static function init(){
		self::$require_admin=array('admin','add_activity','admin_activity','delete_activity','edit_activity','delete_user_activity_award');
		self::$require_log_in=array('apply');
		self::$require_id=array('delete_activity','edit_activity','delete_user_activity_award');
	}


	function apply(){
		if(is_post()){
			$user=User::load($_SESSION['user_id']);
			$user->set('is_applicant',1);
			$user->update();
			server_response(1,'申请成功');
		}
		$user=User::load($_SESSION['user_id']);
		$is_applicant=$user->is_applicant;
		if ($is_applicant)
		show_message('您已经申请了奖学金了。');

		register_smarty();
	}



	function admin(){
		$array=array();
		if(isset($_GET['type'])&&$_GET['type']=='delete_status')
		$array['is_delete']='yes';
		else
		$array['is_delete']='no';
		
		
		$award_activities=AwardActivity::get_objects($array);
		$award_activities=Model::snormalize($award_activities);
		register_smarty(array('award_activities'=>$award_activities));
	}

	function delete_activity(){
		global $c;
		if(is_post()){
			$activity=AwardActivity::load($c);
			$ret=$activity->delete();
			server_response(1);
		}
	}

	function restore_activity(){
		global $c;
		$activity=AwardActivity::load($c);
		$ret=$activity->restore();
		server_response(1);
	}

	function add_activity(){
		if(is_post()){
			$award_activity=AwardActivity::create($_POST);
			$ret=$award_activity->insert();
			if($ret===false)
			echo_object_error($award_activity,'创建失败');
			else
			server_response(1,$ret);
		}
		$activities=Activity::get_objects(array('is_delete'=>'no'));
		Model::snormalize($activities);
		$activity_options=array();
		foreach($activities as $a){
			$activity_options[$a->id]=$a->name;
		}
		register_smarty(array('activity_options'=>$activity_options));

	}

	function edit_activity(){
		global $c,$_SGLOBAL;
		if(is_post()){
			$new_name=$_POST['name'];
			$award_activity=AwardActivity::load($c);
			$award_activity->name=$new_name;
			$award_activity->update();

			if(!empty($_POST['award_name'])&&!empty($_POST['score'])){
				$activity_award=ActivityAward::create(array('name'=>$_POST['award_name'],'score'=>$_POST['score'],'award_activity_id'=>$c));
				$activity_award->insert();
			}

			if(!empty($_POST['user_student_number'])&&!empty($_POST['activity_award_id'])){
				$user=User::get_object(array('student_number'=>$_POST['user_student_number']));
				if($user===false){
					server_response(-1,'用户不存在');
				}



				$activity_award=ActivityAward::load($_POST['activity_award_id']);

				$award_activity=AwardActivity::load($activity_award->award_activity_id);

				$user->give_activity_award($_POST['activity_award_id']);

				sendAnnouncement($user->get_id(),"您在{$award_activity->name}活动中获得了{$activity_award->score}个积分");
			}

			server_response(1);
		}

		$sql="select r.id as id,u.name as user_name,a.name as award_name from user_activity_award_record r,user u,activity_award a where r.user_id=u.id and r.activity_award_id=a.id and r.is_delete='no' and a.award_activity_id={$c}";
		$award_users=$_SGLOBAL['db']->fetch_all($sql);
		$award_activity=AwardActivity::load($c);
		$awards=ActivityAward::get_objects(array('award_activity_id'=>$c,'is_delete'=>'no'));
		$awards=Model::snormalize($awards);

		register_smarty(array('awards'=>$awards,'award_activity'=>$award_activity,'award_users'=>$award_users));

	}

	function delete_activity_award(){
		global $c;
		$activity_award=ActivityAward::load($c);
		$activity_award->delete();
		server_response(1);
	}

	function delete_user_activity_award(){
		global $c;
		$record=UserActivityAwardRecord::load($c);
		$record->delete();
		server_response(1);
	}


}
AwardController::init();
?>