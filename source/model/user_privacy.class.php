<?php
/**
 * This file is generate from table:
 * TABLE_NAME: user_privacy
 * TABLE_COMMENT:
 */

class UserPrivacy extends Model{

	public  static $table_name='user_privacy';
	public  static $class_name='UserPrivacy';
	public  static $id_name='user_id';
	
	protected  static $translate=array();
	protected  static $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();


	static function  init(){
		self::$translate=array(
		'name'=>'姓名'
		,'gender'=>'性别'
		,'telnum'=>'手机'
		,'email'=>'邮箱'
		,'grade'=>'年级'
		,'institute'=>'学院'
		);

		self::$unique=array('user_id');

		self::$require_not_null=array('user_id');
	}



}

UserPrivacy::init();