<?php
/**
 * This file is generate from table:
 * TABLE_NAME: user
 * TABLE_COMMENT:
 */



class User extends Model{

	public  static $table_name='user';
	public  static $class_name='User';
	public  static $id_name='id';

	protected  static $translate=array();
	protected static  $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();

	private static $login=array();
	private static $privacy=array();

	public static function init(){
		self::$require_not_null=array('name','id_card_number','student_number','password','alias_name');
		self::$login=array('name'=>'student_number','password'=>'password');
		self::$translate=array(
		'name'=>'姓名'
		,'alias_name'=>'昵称'
		,'gender'=>'性别'
		,'password'=>'密码'
		,'id_card_number'=>'身份证'
		,'student_number'=>'学号'
		,'telnum'=>'手机'
		,'email'=>'邮箱'
		,'institute'=>'学院'
		,'description'=>'个人简介',
		'credit_grade'=>'学分绩',
		'total_score'=>'总分数',
		'follow_count'=>'粉丝数',
		'grade'=>'年级',
		'institute'=>'学院'
		);

		self::$unique=array('id_card_number','student_number','id','email','telnum');
		self::$unchanged=array('id_card_number','student_number');

		self::add_reg_validate('id_card_number','/^\d{15}$|^\d{17}(?:\d|x|X)$/');
		self::add_reg_validate('student_number','/^\d{9}$/');
		self::add_reg_validate('name','/^\S{2,20}$/','名字过长或过短');
		self::add_reg_validate('alias_name','/^\S{0,50}$/','昵称太长');
		self::add_reg_validate('gender','/^0|1$/');
		self::add_reg_validate('telnum','/^1\d{10}$/');
		self::add_reg_validate('email','/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/');

		self::$privacy['secret']=array('id_card_number','student_number');
		self::$privacy['select']=array('email','telnum','gender','name','institute');

	}



	public function normalize(){
		global $_SGLOBAL;
		if(isset($this->attrs['institute']))
		$this->attrs['institute']=$_SGLOBAL['enum']['institute'][$this->attrs['institute']];
		if(isset($this->attrs['gender']))
		$this->attrs['gender']=$_SGLOBAL['enum']['gender'][$this->attrs['gender']];
	}

	/**
	 * 用户登录
	 * 登录成功，返回用户信息
	 * 登录失败，返回NULL
	 * @status tested
	 * @param $stuid  学生学号
	 * @param $password  密码
	 * @return 单个user数组，如果找不到就为 NULL
	 */
	function login($student_number,$password){
		$result=get_element_by_key('user', 'student_number', $student_number,"id,salt");
		$real_password=md5($result['salt'].md5($password));
		$result=get_element('user','*'," id='{$result['id']}' and password='$real_password' and is_delete='no'");
		if (count($result[0])==0){
			$this->error_msg='用户名或密码错误！';
			return false;
		}else {
			$res=$result[0];
			unset($res['password']);
			unset($res['salt']);
			$this->attrs=$res;
			return true;
		}
	}

	function post_delete(){
		$user_privacy=UserPrivacy::load($this->get_id());
		$user_privacy->delete();
	}


	/**
	 * 用户注册
	 * @status tested
	 * @param $user 里面包含学号等信息，所有的信息都符合格式，直接插入，其中，密码应该是原始密码
	 * @return id号成功 false失败
	 */
	function register(){
		$this->attrs['register_time']=time();
		$this->attrs['session_cookie_user_code']=randcode(50);
		$this->attrs['salt']=randcode(20);
		$this->attrs['password']=md5($this->attrs['salt'].md5($this->attrs['password']));


		$id=$this->insert();
		if($id===false){
			return false;
		}


		$user_privacy=array();
		$user_privacy['user_id']=$id;
		$user_privacy_object=UserPrivacy::create($user_privacy);
		$user_privacy_object->insert();
		

		return $id;
	}

	function change_password($old_pw,$new_pw){
		$user_id=$this->get_id();
		$result=get_element_by_key('user', 'id', $user_id,"id,salt,password");
		$real_password=md5($result['salt'].md5($old_pw));
		if ($result['password']==$real_password) {
			$new_real_password=md5($result['salt'].md5($new_pw));
			updatetable('user', array('password'=>$new_real_password), "id=$user_id");
			return true;
		}else {
			$this->error_msg='原密码错误！';
			return false;
		}
	}

//	/**
//	 *
//	 * @param $like_id
//	 * @return false已经投过票了
//	 */
//	function like($like_id){
//		$liked_id=$this->get_id();
//		$ret=check_if_exist('like_user',array('like_id'=>$like_id,'liked_id'=>$liked_id));
//		if($ret)
//		server_response(JS_RESPONSE_ALERT,'您已经顶过了');
//
//		inserttable('like_user',array('like_id'=>$like_id,'liked_id'=>$liked_id));
//		$this->attrs['follow_count']++;
//		$this->update();
//	}

	function give_activity_award($activity_award_id){
		$record=UserActivityAwardRecord::create(array('user_id'=>$this->get_id(),'activity_award_id'=>$activity_award_id));
		$record->insert();
	}

	private function get_user_status(){
		$this->online_status=time()-$this->lastactivity<120?1:0;
	}

	protected function post_load(){
		$this->get_user_status();
	}



}

User::init();
