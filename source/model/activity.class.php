<?php
/**
 * This file is generate from table:
 * TABLE_NAME: activity
 * TABLE_COMMENT:
 */

class Activity extends Model{


	public  static $table_name='activity';
	public  static $class_name='Activity';
	public  static $id_name='id';

	protected  static $translate=array();
	protected  static $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();

	static function  init(){
		self::$translate=array(
		'name'=>'名称'
		,'content'=>'介绍'
		,'desc'=>'简介'
		);
		self::$require_not_null=array('name','content','desc');
		self::$unique=array('name');
	}

	protected function pre_delete(){
		$aa_list=ActivityAnnouncement::get_objects(array('activity_id'=>$this->get_id()));
		foreach($aa_list as $k=>$v){
			$aa_list[$k]->delete();
		}
	}

	protected function post_restore(){
		$aa_list=ActivityAnnouncement::get_objects(array('activity_id'=>$this->get_id()));
		foreach($aa_list as $k=>$v){
			$aa_list[$k]->restore();
		}
	}




}

Activity::init();