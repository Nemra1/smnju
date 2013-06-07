<?php
/**
 * This file is generate from table:
 * TABLE_NAME: applicant_award_record
 * TABLE_COMMENT:
 */

class UserActivityAwardRecord extends Model{

	public  static $table_name='user_activity_award_record';
	public  static $class_name='UserActivityAwardRecord';
	public static $id_name='id';

	protected  static $translate=array();
	protected  static $require_not_null=array();
	protected  static $unique=array();
	protected  static $unchanged=array();
	protected  static $reg_validate=array();



	static function init(){
		self::$require_not_null=array('user_id','activity_award_id');
	}

	protected function post_insert(){
		$user_news=News::create();
		$user_news->news_type=News::$news_type['award_in_activity'];
		$user_news->news_role=News::$news_role['user'];
		$user_news->fid=$this->get_id();

		$array=array();

		$activity_award=ActivityAward::load($this->activity_award_id);
		//得奖排名或者奖项的名称，比如：第一名、最佳人气奖
		$award_item_name=saddslashes($activity_award->name);
		$score=$activity_award->score;
		$award_activity=AwardActivity::load($activity_award->award_activity_id);
		//具体到某年的某活动的奖名称，比如2011年十大歌手奖项
		$award_activity_name=saddslashes($award_activity->name);
		$activity=Activity::load($award_activity->activity_id);
		//某每年都开展的活动的id和名称，用来导航到该活动
		$activity_id=$activity->id;
		$activity_name=saddslashes($activity->name);
		$json="{\"award_item_name\":\"{$award_item_name}\",\"score\":\"{$score}\",\"award_activity_name\":\"{$award_activity_name}\",\"activity_id\":\"{$activity_id}\",\"activity_name\":\"{$activity_name}\"}";
		$json=saddslashes($json);
		$user_news->tpl_array_json=$json;
		$user_news->insert();
	}

	protected function pre_delete(){
		$news_type=News::$news_type['award_in_activity'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->delete();
	}
	
	protected function pre_restore(){
		$news_type=News::$news_type['award_in_activity'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->restore();
	}


}

UserActivityAwardRecord::init();