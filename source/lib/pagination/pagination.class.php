<?php
abstract class Pagination {
	protected $m_Sql;
	//$m_sql除去select * from 之外的后面的部分
	protected $m_PostSql;
	//protected $m_ObjectClassName;
	protected $m_ObjectPerPage = 10;
	protected $m_Url;
	protected $m_CommonInfo = NULL;
	protected $m_PagerCatelogLength = 9;
	protected $m_PageNo;
	protected $m_PagerCatalog = NULL;
	//在html中显示的列表的class
	protected $m_ListClassName;
	protected static $s_DB;
	protected static $s_SiteUrl;
	public function __construct($value = '') {

	}

	/**
	 * 这个函数需要在子类中实现
	 */
	protected abstract function GenHtmlOfSingleObject($object);

	public function GenerateHtml($page_no) {
		$this -> m_PageNo = empty($page_no)||$page_no<0?1:$page_no;

		$info = $this -> GetCommonInfo();

		$res = &$this -> GetObjectsWithLimit();
		$content = "<div class='$this->m_ListClassName'>";
		$content .= $this -> GetPagerTop();

		foreach ($res as &$value) {
			$content .= $this -> GenHtmlOfSingleObject($value);
		}

		$content .= $this -> GetPagerBottom();
		$content .= "</div>";
		return $content;
	}

	public function SetSql($sql) {
		$this -> m_Sql = $sql;
	}

	public function SetObjectPerPage($num) {
		$this -> m_ObjectPerPage = $num;
	}

	public function SetUrl($url) {
		$this -> m_Url = $url;
	}

	public function SetPageNo($page_no) {
		$this -> m_PageNo = $page_no;
	}

	public static function SetDB($db) {
		self::$s_DB = $db;
	}

	public static function SetSiteUrl($site_url) {
		self::$s_SiteUrl = $site_url;
	}

	protected function GetObjectsCount() {
		$result_number = self::$s_DB -> fetch_all("SELECT COUNT(*) as count FROM " . $this -> m_PostSql);
		$result_number = $result_number[0]['count'];
		return $result_number;
	}

	protected function GetObjectsWithLimit() {
		$sql = $this -> m_Sql . " LIMIT " . ($this -> m_PageNo - 1) * $this -> m_ObjectPerPage . " , " . $this -> m_ObjectPerPage;
		$res = self::$s_DB -> fetch_all_as_object($sql);

		return $res;
	}

	protected function GetCommonInfo() {
		if ($this -> m_CommonInfo != NULL) {
			return $this -> m_CommonInfo;
		}
		$ret_val = array();
		//总数
		$ret_val['total_count'] = $this -> GetObjectsCount();
		$ret_val['start'] = ($this -> m_PageNo - 1) * $this -> m_ObjectPerPage + 1;
		$ret_val['end'] = $ret_val['start'] + $this -> m_ObjectPerPage - 1;
		//总页数
		$ret_val['total_page_count'] = ceil($ret_val['total_count'] / $this -> m_ObjectPerPage);
		return $ret_val;
	}

	protected function GetPagerTop() {
		$info = $this -> GetCommonInfo();
		$content = "<div class='pager_top'>";
		$content .= "<span>当前显示" . $info['start'] . "-" . $info['end'] . "共" . $info['total_count'] . "</span>";
		$content .= $this -> GetPagerCatalog();
		$content .= "</div>";
		return $content;
	}

	protected function GetPagerBottom() {
		$content = "<div class='pager_bottom'>";
		$content .= $this -> GetPagerCatalog();
		$content .= "</div>";
		return $content;
	}

	private function GetPageListItem($page_no, $text) {
		$content = "<li>";
		$content .= "<a class='chn' href='" . $this -> m_Url . "?page_no=" . $page_no . "'>";
		$content .= $text;
		$content .= "</a>";
		$content .= "</li>";
		return $content;
	}

	/**
	 * 得到分页器的目录html，如：首页，上一页，3，4，5，6，7，下一页，尾页
	 */
	protected function GetPagerCatalog() {
		//生成一次后，就不要重复运算了
		if ($this -> m_PagerCatalog != NULL) {
			return $this -> m_PagerCatalog;
		}
		$info = $this -> GetCommonInfo();
		$content = "<ol class='pager_catalog'>";
		//首页
		if ($this -> m_PageNo > ceil($this -> m_PagerCatelogLength / 2)) {
			$content .= $this -> GetPageListItem(1, "首页");
		}
		//上一页
		if ($this -> m_PageNo > 1) {
			$content .= $this -> GetPageListItem($this -> m_PageNo - 1, "上一页");
		}
		$start_page_no = ($tmp = ($this -> m_PageNo - ceil($this -> m_PagerCatelogLength / 2))) < 1 ? 1 : $tmp;
		$end_page_no = ($tmp = ($this -> m_PageNo + ceil($this -> m_PagerCatelogLength / 2))) > $info['total_page_count'] ? $info['total_page_count'] : $tmp;
		for ($i = $start_page_no; $i < $this -> m_PageNo; ++$i) {
			$content .= $this -> GetPageListItem($i, "$i");
		}

		$content .= "<li>";
		//$content .= "<a class='current' href='#nogo'>";
		$content .= "$this->m_PageNo";
		//$content .= "</a>";
		$content .= "</li>";

		for ($i = $this -> m_PageNo + 1; $i <= $end_page_no; ++$i) {
			$content .= $this -> GetPageListItem($i, "$i");
		}
		//下一页
		if ($this -> m_PageNo < $info['total_page_count']) {
			$content .= $this -> GetPageListItem($this -> m_PageNo + 1, "下一页");
		}
		//尾页
		if ($info['total_page_count'] - $this -> m_PageNo >= ceil($this -> m_PagerCatelogLength / 2)) {
			$content .= $this -> GetPageListItem($info['total_page_count'], "尾页");
		}
		$content .= "</ol>";
		$this -> m_PagerCatalog = $content;
		return $content;
	}

}

Pagination::SetDB($_SGLOBAL['db']);
Pagination::SetSiteUrl($_SC['siteurl']);
?>