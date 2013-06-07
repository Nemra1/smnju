<?php
/**
 * This file is generate from table:
 * TABLE_NAME: activity_announcement
 * TABLE_COMMENT: 这个表是具体举办的活动，有活动举办的具体内容，还有获奖者等可以和它关联
 */

class ActivityAnnouncement extends Model
{

	public static $table_name = 'activity_announcement';
	public static $class_name = 'ActivityAnnouncement';
	public static $id_name = 'id';


	protected static $translate = array();
	protected static $require_not_null = array();
	protected static $unique = array();
	protected static $unchanged = array();
	protected static $reg_validate = array();


	static function init()
	{
		self::$translate = array(
             'name' => '标题'
             , 'content' => '内容'
             , 'desc' => '简介'
             );

             self::$require_not_null = array('name', 'content', 'desc');
	}

	protected function post_insert()
	{
		$site_news=News::create();
		$site_news->news_type=News::$news_type['create_activity_announcement'];
		$site_news->news_role=News::$news_role['site'];
		$site_news->fid=$this->id;

		$this->normalize();

		$aa_name=saddslashes($this->name);
		$aa_desc=saddslashes($this->desc);

		$json="{\"aa_id\":\"{$this->id}\",\"aa_name\":\"{$aa_name}\",\"aa_desc\":\"{$aa_desc}\",\"activity_id\":\"{$this->activity_id}\",\"aa_create_time\":\"{$this->create_time}\"}";

		$site_news->tpl_array_json=saddslashes($json);
		$site_news->insert();
	}

	protected function post_update(){
		$news_type=News::$news_type['create_activity_announcement'];
		$news=News::get_object(array('fid'=>$this->id,'news_type'=>$news_type));
		if($news===false)
		return false;

		$this->normalize();

		$aa_name=saddslashes($this->name);
		$aa_desc=saddslashes($this->desc);
		$json="{\"aa_id\":\"{$this->id}\",\"aa_name\":\"{$aa_name}\",\"aa_desc\":\"{$aa_desc}\",\"activity_id\":\"{$this->activity_id}\",\"aa_create_time\":\"{$this->create_time}\"}";
		$news->tpl_array_json=saddslashes($json);
		$news->update();
	}

	protected function pre_delete(){
		$news_type=News::$news_type['create_activity_announcement'];
		$news=News::get_object(array('fid'=>$this->id,'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->delete();
	}

	protected function post_restore(){
		$news_type=News::$news_type['create_activity_announcement'];
		$news=News::get_object(array('fid'=>$this->id,'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->restore();
	}

}

ActivityAnnouncement::init();