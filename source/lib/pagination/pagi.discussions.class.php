<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lubo
 * Date: 03/12/11
 * Time: 02:33
 * To change this template use File | Settings | File Templates.
 */
class PagiDiscussions extends Pagination
{

    public function __construct($table_name, $where_sql, $order_by, $url)
    {
        parent::__construct();
        $this->m_PostSql = " " . $table_name . " WHERE " . $where_sql . " ORDER BY " . $order_by;
        $this->m_Sql = "SELECT * FROM " . $this->m_PostSql;
        $this->m_Url = self::$s_SiteUrl . $url;
        $this->m_ListClassName = 'discussion_list';
    }

    protected function GenHtmlOfSingleObject($object)
    {
        $array = array();
        $object->edit_url = self::$s_SiteUrl . "/discussion/edit/$object->id";
        $object->delete_url = self::$s_SiteUrl . "/discussion/delete/$object->id";
        $object->show_url = self::$s_SiteUrl . "/discussion/show/$object->id";
        $object->brief_content = utf_substr($object->content, 200);
        //$object->create_time=getFormatedTime($object->create_time);
        $topic = Discussion::load($object->topic_id);

        $topic->show_url = self::$s_SiteUrl . "/topic/show/$topic->id";

        $array['discussion'] = $object;
        $array['topic'] = $topic;
        $content = fetch_smarty($array, 'discussion/discussion_brief.tpl');

        return $content;
    }
}

?>