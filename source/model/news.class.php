<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Lubo
 * Date: 14/12/11
 * Time: 14:02
 */

class News extends Model
{
	public static $table_name = 'news';
	public static $class_name = 'News';
	public static $id_name = 'id';
	protected static $translate = array();
	protected static $require_not_null = array();
	protected static $unique = array();
	protected static $unchanged = array();
	protected static $reg_validate = array();

	public static $news_role=array();
	public static $news_type=array();
	public static $news_type_of_site = array();

	public static function init()
	{
		self::$require_not_null = array('user_id', 'tpl_array_json', 'news_type', 'news_role');

		self::$news_type=array(
            'update_topic'=>'update_topic',
            'create_discussion'=>'create_discussion',
            'create_topic'=>'create_topic',
            'create_article'=>'create_article',
            'create_site_announcement'=>'create_site_announcement',
            'create_activity_announcement'=>'create_activity_announcement',
            'article_result'=>'article_result',
            'award_in_activity'=>'award_in_activity',
            'award_in_article'=>'award_in_article');

		self::$news_role=array('user'=>'user','site'=>'site');

		self::$news_type_of_site = array(
            'create_topic',
            'create_site_announcement',
            'create_activity_announcement',
            'article_result');
	}

	protected function pre_insert()
	{
		//如果没有设置user_id,说明是调用insert用户的news
		//但是有些情况并不是当前插入数据的用户id,比如获奖时,user_id应该是获奖用户的id,而不是插入该数据的管理员id
		if (!isset($this->user_id)) {
			$this->user_id = $_SESSION['user_id'];
		}
		if (!isset($this->news_role)) {
			$this->news_role = in_array($this->news_type, self::$news_type_of_site) ? 'site' : 'user';
		}
	}

	
}

News::init();
?>