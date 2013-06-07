<?php
/**
 * This file is generate from table:
 * TABLE_NAME: topic
 * TABLE_COMMENT:
 */


class Topic extends Model
{

	public static $table_name = 'topic';
	public static $class_name = 'Topic';
	public static $id_name = 'id';

	protected static $translate = array();
	protected static $require_not_null = array();
	protected static $unique = array();
	protected static $unchanged = array();
	protected static $reg_validate = array();


	static function init()
	{
		self::$require_not_null = array('name', 'content', 'due_time');
		self::$translate = array('due_time' => '截止日期', 'content' => '内容', 'name' => '标题','desc'=>'简介');

	}


	function normalize()
	{

		if (!$this->is_normalized) {
			$this->attrs['create_time'] = date('Y-m-d', $this->attrs['create_time']);
			$temps = explode('-', substr($this->attrs['due_time'], 0, 10));
			$this->set('due_year', $temps[0]);
			$this->set('due_month', $temps[1]);
			$this->set('due_day', $temps[2]);
		}
	}

    /**
     * 1.管理员创建主题之后,要在网站新鲜事里插入该新主题信息
     * 2.创建某个主题之后，自动新建一个置顶的讨论贴，这个讨论贴内容和主题是一样的
     * @return unknown_type
     */
    protected function post_insert()
    {
        //1,网站新鲜事里插入该新主题信息
        $site_news = News::create();
        $site_news->news_type = News::$news_type["create_topic"];
        $site_news->news_role = News::$news_role['site'];
        $site_news->fid=$this->get_id();

		$this->normalize();

        $topic_name = saddslashes($this->name);
        $topic_desc = saddslashes($this->desc);
        $json = "{\"topic_id\":\"{$this->id}\",\"topic_name\":\"{$topic_name}\",\"topic_desc\":\"{$topic_desc}\",\"topic_create_time\":\"{$this->create_time}\"}";
        $site_news->tpl_array_json = saddslashes($json);
        $site_news->insert();

        //2,自动新建一个置顶的讨论贴
        $attrs=array(
            'name'=>$this->name,
            'content'=>$this->content,
            'is_top'=>'yes',
            'topic_id'=>$this->id,
            'user_id'=>$this->creator_id
        );

        $discussion=Discussion::create($attrs);
        $discussion->insert();
    }

	protected function post_update()
	{
		$news_type = News::$news_type['update_topic'];
		$news = News::get_objects(array('fid' => $this->id, 'news_type' => $news_type));
		if (!empty($news)){
			$news = $news[0];
		}else{
			return;
		}

		$topic_name = saddslashes($this->name);
		$topic_desc = saddslashes($this->desc);
		$json = "{\"topic_id\":\"{$this->get_id()}\",\"topic_name\":\"{$topic_name}\",\"topic_desc\":\"{$topic_desc}\",\"topic_create_time\":\"{$this->create_time}\"}";

		$news->tpl_array_json = saddslashes($json);
		$news->update();
	}

    public function increase_read_count()
    {
        $this->read_count++;
        $this->update();
    }

	protected function pre_delete(){
		$discussions=Discussion::get_objects(array('topic_id'=>$this->get_id()));
		foreach ($discussions as $k=>$v){
			$discussions[$k]->delete();
		}
			
		$news_type=News::$news_type['create_topic'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->delete();

	}

	protected function post_restore(){
		$discussions=Discussion::get_objects(array('topic_id'=>$this->get_id()));
		foreach ($discussions as $k=>$v){
			$discussions[$k]->restore();
		}
		
		$news_type=News::$news_type['create_topic'];
		$news=News::get_object(array('fid'=>$this->get_id(),'news_type'=>$news_type));
		if($news===false)
		return false;

		$news->restore();
	}

    /**
     * 增加评论数
     */
    public function increase_comment_count()
    {
        $this->comment_count++;
        $this->update();
    }
    /**
     * 增加主题下的讨论数
     */
    public function increase_discussion_count()
    {
        $this->discussion_count++;
        $this->update();
    }
    /**
     * 增加主题下的文章数
     */
    public function increase_article_count(){
        $this->article_count++;
        $this->update();
    }
    
    /**
     * 减少评论数
     */
    public function decrease_comment_count()
    {
        $this->comment_count++;
        $this->update();
    }
    /**
     * 减少主题下的讨论数
     */
    public function decrease_discussion_count()
    {
        $this->discussion_count++;
        $this->update();
    }
    /**
     * 减少主题下的文章数
     */
    public function decrease_article_count(){
        $this->article_count++;
        $this->update();
    }
}

Topic::init();
?>