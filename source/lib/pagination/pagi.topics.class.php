<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lubo
 * Date: 03/12/11
 * Time: 03:01
 * To change this template use File | Settings | File Templates.
 */
class PagiTopics extends Pagination
{

    /***
     * @param $table_name
     * @param $where_sql
     * @param $order_by
     * @param $url  开头为'/'的相对于siteurl的目录路径
     */
    public function __construct($table_name, $where_sql, $order_by, $url)
    {
        parent::__construct();
        $this->m_PostSql = " " . $table_name . " WHERE " . $where_sql . " ORDER BY " . $order_by;
        $this->m_Sql = "SELECT * FROM " . $this->m_PostSql;
        $this->m_Url = self::$s_SiteUrl . $url;
        $this->m_ListClassName = 'topic_list';
    }

    protected function GenHtmlOfSingleObject($object)
    {
        $array = array();
        $object->edit_url = self::$s_SiteUrl . "/topic/edit/$object->id";
        $object->delete_url = self::$s_SiteUrl . "/topic/delete/$object->id";
        $object->show_url = self::$s_SiteUrl . "/topic/show/$object->id";
        $object->brief_content = utf_substr($object->content, 200);
        //$object->create_time=getFormatedTime($object->create_time);

        $array['topic'] = $object;
        $content = fetch_smarty($array, 'topic/topic_brief.tpl');

        return $content;
    }
}