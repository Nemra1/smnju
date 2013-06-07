<?php
/**
 * This file is generate from table:
 * TABLE_NAME: activity_award
 * TABLE_COMMENT:
 */

class ActivityAward extends Model{

	public  static $table_name='activity_award';
	public  static $class_name='ActivityAward';
	public  static $id_name='id';
	
	protected  static $translate=array();
	protected  static $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();


	static function init(){
		self::$translate=array(
		'name'=>'奖项'
		,'score'=>'分数'
		);
		self::$require_not_null=array('name','score','award_activity_id');
	}
	


}

ActivityAward::init();