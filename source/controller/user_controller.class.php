<?php
class UserController extends Controller{

	protected  static $require_admin=array();
	protected  static $require_log_in=array();
	protected  static $require_visitor=array();
	protected  static $require_id=array();
	protected static $me_or_other=array();
	protected static $pag=array();

	static function init(){
		self::$require_admin=array('admin','admin_single','admin_search');
		self::$require_log_in=array('edit','logout','avatar','change_password','privacy'
		,'avatar_register','privacy_register');
		self::$require_visitor=array('login','register');
		self::$require_id=array('like','user_pag_index');
		self::$me_or_other=array('profile','show');
		self::$pag=array('index_pag','news_pag');
	}


	function index(){
		$user_list_content=$this->index_pag_html(1);
		register_smarty(array('user_list_content' => $user_list_content));
	}



	function index_pag(){
		global $c;
		$ret=$this->index_pag_html($c);
		echo $ret;
	}


	private function index_pag_html($cur_page){

		$pagination=new Pagination(10,$cur_page,5,site_url('/user/index_pag'),'#user_index_list');

		$users=$pagination->fetch_objects('User',array('is_delete'=>'no'),'create_time asc');
		$users=Model::snormalize($users);

		$arr=array();
		foreach($users as $user){
			$user_privacy=UserPrivacy::load($user->id);
			$user_profile=$user;
			foreach($user as $k=>$v){
				if(isset($user_privacy->$k) && $user_privacy->$k===0)
				unset($user_profile->$k);
			}
			$arr[]=$user_profile;
		}

		$html=$pagination->generate_html('user/index_pag.tpl',array('users'=>$arr));
		return $html;

	}

	function show(){
		global $c;
		$user=User::load($c);
		if($user===false)
		show_message('无此用户！');

		$user->normalize();

		$user_privacy=UserPrivacy::load($c);


		$user_profile=$user;
		foreach($user as $k=>$v){
			if(isset($user_privacy->$k) && $user_privacy->$k===0)
			unset($user_profile[$k]);
		}

		$news_list_content=$this->news_pag_html($c,1);
		register_smarty(array('news_list_content'=>$news_list_content,'user'=>$user,'user_profile'=>$user_profile));
	}

	function news_pag(){
		global $c,$d;
		$news_list_html =  $this->news_pag_html($c,$d);
		echo $news_list_html;
	}


	private function news_pag_html($c,$d){
		$pagination=new Pagination(10,$d,5,site_url("/user/news_pag/$c/"),'#news_area');

		$news_list=$pagination->fetch_objects('News',array('news_role'=>'user','user_id'=>$c,'is_delete'=>'no'),'create_time desc');
		$news_list_html=generate_news_list_html($news_list);
		$html=$pagination->generate_html('news/pag.tpl',array('news_list_html'=>$news_list_html));
		return $html;
	}


	function login(){
		if(is_post()){
			$student_number=$_POST['student_number'];
			$password=$_POST['password'];
			$user=User::create();
			$ret=$user->login($student_number,$password);
			if($ret===false){
				echo_object_error($user,'登陆错误');
			}
			$_SESSION['user_id']=$user->id;
			$_SESSION['user_name']=$user->name;
			$_SESSION['alias_name']=$user->alias_name;
			$_SESSION['role']=$user->role;
			setcookie('SCUC',$user->session_cookie_user_code,time()+30*24*3600);
			setcookie('user_id',$_SESSION['user_id'],time()+30*24*3600);
			server_response(1);
		}else{
			register_smarty();
		}
	}


	function register(){
		global $_SGLOBAL;
		$_SESSION['register_process']=1;
		if (is_post()){
			$user=$_POST;
			$user_object=User::create($user);
			$ret_id=$user_object->register();
			if($ret_id===false){
				echo_object_error($user_object,'注册失败');
			}
			setcookie('SCUC',$user_object->get('session_cookie_user_code'),time()+30*24*3600);
			setcookie('user_id',$ret_id,time()+30*24*3600);
			$_SESSION['user_id']=$ret_id;
			$_SESSION['user_name']=$user_object->get('name');
			$_SESSION['alias_name']=$user_object->get('alias_name');
			server_response(1);
		}else{
			register_smarty();
		}
	}

	function avatar_register(){
		if($_SESSION['register_process']!=1){
			show_message('访问出错',-1);
		}
		$this->avatar();

	}

	function privacy_register(){
		if($_SESSION['register_process']!=1){
			show_message('访问出错',-1);
		}
		$this->privacy();
	}

	function privacy(){
		if(is_post()){
			$user_privacy=$_POST;
			$user_privacy_object=UserPrivacy::load($_SESSION['user_id']);
			$user_privacy_object->merge_attrs($user_privacy);
			$ret=$user_privacy_object->update();
			if($ret===false){
				echo_object_error($user_privacy_object,'修改失败！');
			}
			unset($_SESSION['register_process']);
			server_response(1);
		}
		$user_privacy=UserPrivacy::load($_SESSION['user_id']);
		register_smarty(array('user_privacy'=>$user_privacy));

	}



	function edit(){
		global $_SGLOBAL;
		$user_id=$_SESSION['user_id'];

		if (is_post()){
			$user=User::load($user_id);
			$user->merge_attrs($_POST);
			$ret=$user->update();
			if($ret===false){
				echo_object_error($user,'修改失败');
			}

			$_SESSION['alias_name']=$_POST['alias_name'];
			server_response(1);
		}else{
			$user=User::load($user_id);
			register_smarty(array('user'=>$user));
		}
	}


	function logout($redirect=1){
		unset($_SESSION['user_id']);
		unset($_SESSION['alias_name']);
		unset($_SESSION['user_name']);
		unset($_SESSION['role']);
		setcookie('SCUC','',time() - 113600,'/');
		setcookie('user_id','',time() - 113600,'/');

		if($redirect==1)
		redirect_page('/');
	}

	function avatar(){
		$user_id=$_SESSION['user_id'];
		if(is_post() && !empty($_FILES['avatar'])){

			$ret=uploadimg('avatar',S_ROOT.'./upload/avatar/',array('gif','jpg','png'));

			if($ret==-1)
			server_response(-1,'图片格式错误');
			if($ret==-2)
			server_response(-1,'图片太大');
			if($ret==-3)
			server_response(-1,'图片复制失败');

			$user['avatar']=$ret;
			$user['medium_avatar']=makethumb_by_height(S_ROOT.'./upload/avatar/'.$user['avatar'],200,S_ROOT.'./upload/avatar/');
			$user['little_avatar']=makethumb_by_height(S_ROOT.'./upload/avatar/'.$user['avatar'],50,S_ROOT.'./upload/avatar/');
			$user_object=User::load($user_id);
			$user_object->merge_attrs($user);
			$ret=$user_object->update();
			if($ret===false){
				echo_object_error($user_object,'更换头像出错');
			}
			server_response(1,site_url('/upload/avatar/'.$user_object->medium_avatar));
		}else{
			$user=User::load($user_id);
			register_smarty(array('user'=>$user));
		}
	}



	function change_password(){
		if(is_post()){
			$user_id=$_SESSION['user_id'];
			$user=User::load($user_id);
			$ret=$user->change_password($_POST['old_password'],$_POST['password']);
			if($ret===false){
				echo_object_error($user,'密码更改错误');
			}
			$this->logout(0);
			server_response(1);
		}
		register_smarty();
	}


	function admin_search(){
		$name=$_GET['search_user'];
		$type=$_GET['search_type'];
		if($type=='name'){
			$users=User::get_objects(" name like '%$name%' ");
		}elseif($type=='student_number'){
			$users=User::get_objects(array('student_number'=>$name));
		}elseif($type=='id'){
			$users=User::get_objects(array('id'=>$name));
		}
		$users=Model::snormalize($users);
		register_smarty(array('users'=>$users,'content_tpl'=>'user/admin.tpl'));
	}

	function admin_single(){
		global $c,$_SGLOBAL;
		$user_id=$c;
		if(is_post()){
			$user=array();
			if(isset($_POST['cancel_applicant'])){
				if($_POST['cancel_applicant']=='on')
				$user['is_applicant']=0;
				else
				$user['is_applicant']==1;
			}
			if(isset($_POST['be_applicant'])){
				if($_POST['be_applicant']=='on')
				$user['is_applicant']=1;
				else
				$user['is_applicant']=0;
			}
			if($_POST['delete']=='on')
			$user['is_delete']='yes';
			if($_POST['restore']=='on')
			$user['is_delete']='no';

			$user['credit_grade']=$_POST['credit_grade'];
			$user['follow_count']=$_POST['follow_count'];

			$user_object=User::load($user_id);
			if($user_object===false){
				server_response(-1,'没有此用户');
			}
			$user_object->merge_attrs($user);
			$ret=$user_object->update();
			if($ret===false){
				echo_object_error($user_object,'修改错误');
			}
			server_response(1);
		}
		$user=User::load($user_id);
		if($user===false){
			show_message('没有此用户',-1);
		}
		$user->normalize();
		register_smarty(array('user'=>$user));
	}

	function admin(){
		if($_GET['type']=='delete_status')
		$users=User::get_objects(array('is_delete'=>'yes'));
		else
		$users=User::get_objects(array('is_delete'=>'no'));


		$users=Model::snormalize($users);

		register_smarty(array('users'=>$users));

	}


}


UserController::init();

?>