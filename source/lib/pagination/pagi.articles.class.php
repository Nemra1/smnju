<?php
class PagiArticles extends Pagination
{

    public function __construct($table_name, $where_sql, $order_by, $url)
    {
        parent::__construct();
        $this->m_PostSql = " " . $table_name . " WHERE " . $where_sql . " ORDER BY " . $order_by;
        $this->m_Sql = "SELECT * FROM " . $this->m_PostSql;
        $this->m_Url = self::$s_SiteUrl . $url;
        $this->m_ListClassName = 'article_list';
    }

    protected function GenHtmlOfSingleObject($object)
    {
        $array = array();
        $object->edit_url = self::$s_SiteUrl . "/article/edit/$object->id";
        $object->delete_url = self::$s_SiteUrl . "/article/delete/$object->id";
        $object->show_url = self::$s_SiteUrl . "/article/show/$object->id";
        $object->brief_content = utf_substr($object->content, 200);
        $object->create_time = getFormatedTime($object->create_time);
        $topic = Topic::load($object->topic_id);

        $topic->show_url = self::$s_SiteUrl . "/topic/show/$topic->id";

        $array['article'] = $object;
        $array['topic'] = $topic;
        $content = fetch_smarty($array, 'article/article_brief.tpl');

        return $content;
    }
}

?>