<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lubo
 * Date: 02/12/11
 * Time: 12:39
 * To change this template use File | Settings | File Templates.
 */
class Discussion extends Model implements CommentSubject
{
	public static $table_name = 'discussion';
	public static $class_name = 'Discussion';
	public static $id_name = 'id';

	protected static $translate = array();
	protected static $require_not_null = array();
	protected static $unique = array();
	protected static $unchanged = array();
	protected static $reg_validate = array();

	/**
	 * 1.把该讨论对应的主题的讨论数加1
	 * 2.添加创建讨论的新闻
	 */
	protected function post_insert(){

		$this->normalize();
		//这里的topic可以给下面使用，所以只load一次
		$topic=Topic::load($this->topic_id);
		$topic->increase_discussion_count();

		$user_news=News::create();
		$user_news->news_type=News::$news_type['create_discussion'];
		$user_news->news_role=News::$news_role['user'];
		$user_news->fid=$this->get_id();

		$array=array();
		$topic_id=$topic->id;
		$topic_name=saddslashes($topic->name);

		$discussion_id=$this->id;
		$discussion_name=saddslashes($this->name);
		$discussion_desc=saddslashes(utf_substr(strip_tags($this->content),100));

		$json="{\"topic_id\":\"{$topic_id}\",\"topic_name\":\"{$topic_name}\",\"discussion_id\":\"{$discussion_id}\",\"discussion_name\":\"{$discussion_name}\",\"discussion_desc\":\"{$discussion_desc}\",\"discussion_create_time\":\"{$this->create_time}\"}";
		$json=saddslashes($json);
		$user_news->tpl_array_json=$json;

		$user_news->insert();
	}


	protected function post_update(){

		$this->normalize();
		//这里的topic可以给下面使用，所以只load一次
		$topic=Topic::load($this->topic_id);

		$user_news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>'create_discussion'));

		$array=array();
		$topic_id=$topic->id;
		$topic_name=saddslashes($topic->name);

		$discussion_id=$this->id;
		$discussion_name=saddslashes($this->name);
		$discussion_desc=saddslashes(utf_substr(strip_tags($this->content),100));

		$json="{\"topic_id\":\"{$topic_id}\",\"topic_name\":\"{$topic_name}\",\"discussion_id\":\"{$discussion_id}\",\"discussion_name\":\"{$discussion_name}\",\"discussion_desc\":\"{$discussion_desc}\",\"discussion_create_time\":\"{$this->create_time}\"}";
		$json=saddslashes($json);
		$user_news->tpl_array_json=$json;
		$user_news->update();
	}

	protected function pre_delete(){
		$news_type=News::$news_type['create_discussion'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->delete();

		$topic=Topic::load($this->topic_id);
		$topic->decrease_discussion_count();
	}

	protected function post_restore(){
		$news_type=News::$news_type['create_discussion'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->restore();

		$topic=Topic::load($this->topic_id);
		$topic->increase_discussion_count();
	}

	/**
	 * 增加评论数
	 * 增加讨论的评论时，还需要增加该主题下地讨论总数
	 */
	public function increase_comment_count(){
		$this->comment_count++;
		$this->update();

		$topic=Topic::load($this->topic_id);
		$topic->increase_comment_count();
	}

}
