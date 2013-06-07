<?php
/**
 * This file is generate from table:
 * TABLE_NAME: award_activity
 * TABLE_COMMENT:
 */

class AwardActivity extends Model{

	public  static $table_name='award_activity';
	public  static $class_name='AwardActivity';
	public  static $id_name='id';

	protected  static $translate=array();
	protected  static $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();


	static function init(){
		self::$translate=array('id'=>'id'
		,'name'=>'活动名称'
		,'create_time'=>'创建时间'
		);
		self::$require_not_null=array('name');
	}

	protected function pre_delete(){
		$awards=ActivityAward::get_objects(array('award_activity_id'=>$this->get_id()));
		foreach($awards as $k=>$v){
			$awards[$k]->delete();
		}
	}

	protected function post_restore(){
		$awards=ActivityAward::get_objects(array('award_activity_id'=>$this->get_id()));
		foreach($awards as $k=>$v){
			$awards[$k]->restore();
		}
	}



}
AwardActivity::init();