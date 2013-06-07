<?php
/**
 * This file is generate from table:
 * TABLE_NAME: announcement
 * TABLE_COMMENT:
 */


class SiteAnnouncement extends Model
{

	public static $table_name = 'site_announcement';
	public static $class_name = 'SiteAnnouncement';
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
            );

            self::$require_not_null = array('name', 'content');
            self::$unique = array('name');
	}

	protected function post_insert(){
			
		$site_news=News::create();
		$site_news->news_type=News::$news_type['create_site_announcement'];
		$site_news->news_role=News::$news_role['site'];
		$site_news->fid=$this->get_id();
		

		$this->normalize();
		$sa_name=saddslashes($this->name);
		$sa_desc=saddslashes($this->desc);
		$sa_create_time=$this->create_time;
		$json="{\"sa_id\":\"{$this->get_id()}\",\"sa_name\":\"{$sa_name}\",\"sa_desc\":\"{$sa_desc}\",\"sa_create_time\":\"{$sa_create_time}\"}";
		$site_news->tpl_array_json=saddslashes($json);
		$site_news->insert();
	}

	protected function post_update(){
		$news_type=News::$news_type['create_site_announcement'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;
		

		$this->normalize();

		$sa_name=saddslashes($this->name);
		$sa_desc=saddslashes($this->desc);
		$sa_create_time=$this->create_time;
		$json="{\"sa_id\":\"{$this->get_id()}\",\"sa_name\":\"{$sa_name}\",\"sa_desc\":\"{$sa_desc}\",\"sa_create_time\":\"{$sa_create_time}\"}";
		$news->tpl_array_json=saddslashes($json);
		$news->update();
	}
	
	protected function pre_delete(){
		$news_type=News::$news_type['create_site_announcement'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;
		
		$news->delete();
	}

	protected function post_delete(){
		$news_type=News::$news_type['create_site_announcement'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;
		
		$news->restore();
	}


}

SiteAnnouncement::init();