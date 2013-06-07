<?php
class Controller{
	protected static $require_admin=array();
	protected  static $require_log_in=array();
	protected  static $require_visitor=array();
	protected  static $require_id=array();
	protected static $me_or_other=array();
	protected static $pag=array();

	function invoke($method){
		$this->check_method($method);
		$this->$method();
	}

	protected function check_method($method){
		global $c,$_SGLOBAL;
		if (!method_exists($this,$method))
		show_message('url错误',-1);

		if(in_array($method,static::$require_admin)){
			if(!is_admin()){
				if(in_ajax())
				server_response(-1,'需要管理员身份！');
				else
				show_message('需要管理员身份',-1);
			}

		}

		if(in_array($method,static::$require_log_in)){
			if(!logged_in()){
				if(in_ajax())
				server_response(-1,'需要登录');
				else{
					show_message('需要登录','/login/',-1);
				}
			}
		}


		if(in_array($method,static::$require_visitor)){
			if(logged_in()){
				if(in_ajax())
				server_response(-1,'您已登录，请注销后再进行相关操作');
				else
				show_message('您已登录，请注销后再进行相关操作',-1);
			}
		}



		if(in_array($method,static::$require_id)){
			if($c===null){
				if(in_ajax())
				server_response(-1,'缺少参数！');
				else
				show_message('缺少参数！',-1);
			}
		}


		$_SGLOBAL['smarty']->assign('person_same',true);
		if(in_array($method,static::$me_or_other)){
			if($c===null&& !isset($_SESSION['user_id'])){
				if(in_ajax())
				server_response(-1,'参数缺失！');
				else
				show_message('缺少参数！',-1);
			}
			if($c===null&& isset($_SESSION['user_id'])){
				$c=$_SESSION['user_id'];
			}

			if($c!==null&& isset($_SESSION['user_id'])){
				$person_same=($c===$_SESSION['user_id']);
				$_SGLOBAL['smarty']->assign('person_same',$person_same);
			}

			$owner=User::load($c);
			if($owner===false){
				if(in_ajax())
				server_response(-1,'参数缺失！');
				else
				show_message('缺少参数！',-1);
			}
			$_SGLOBAL['smarty']->assign('owner',$owner);


		}

		if(in_array($method,static::$pag)){
			if(!isset($_GET['page_num'])){
				if(in_ajax())
				server_response(-1,'参数缺失！');
				else
				show_message('缺少参数！',-1);
			}

			$ret=intval($_GET['page_num']);
			if(empty($ret))
			{
				if(in_ajax())
				server_response(-1,'参数异常！');
				else
				show_message('参数异常！',-1);
			}

		}


	}
}
?>