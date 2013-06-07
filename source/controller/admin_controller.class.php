<?php
class AdminController extends Controller{
	protected static $require_admin=array();
	protected  static $require_log_in=array();
	protected  static $require_visitor=array();
	protected  static $require_id=array();
	protected static $me_or_other=array();
	protected static $pag=array();

	static function init(){
		self::$require_admin=array('index');
		self::$require_visitor=array('login_simuren');
	}

	function index(){
		register_smarty();
	}



	function login_simuren(){
		if(is_post()){
			$name=$_POST['name'];
			$password=$_POST['password'];
			$user=admin_login($name,$password);

			if(empty($user))
			show_message('登陆错误','/admin/login_simuren/');

			$_SESSION['user_id']=$user['id'];
			$_SESSION['user_name']=$user['name'];
			$_SESSION['alias_name']=$user['alias_name'];
			$_SESSION['admin']=1;
			setcookie('SCUC',$user['session_cookie_user_code'],time()+30*24*3600);
			setcookie('user_id',$user['id'],time()+30*24*3600);

			redirect_page('/admin/');
		}
		register_smarty();
	}

}

AdminController::init();
?>