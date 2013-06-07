<?php

class dbstuff {
	var $querynum = 0;
	var $link;
	var $histories;

	var $dbhost;
	var $dbuser;
	var $dbpw;
	var $dbcharset;
	var $pconnect;
	var $tablepre;
	var $time;

	var $goneaway = 5;

	/**
	 * 连接数据库
	 * */
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = '', $pconnect = 0, $tablepre='', $time = 0) {
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpw = $dbpw;
		$this->dbname = $dbname;
		$this->dbcharset = $dbcharset;
		$this->pconnect = $pconnect;
		$this->tablepre = $tablepre;
		$this->time = $time;

		if($pconnect) {
			if(!$this->link = mysql_pconnect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		} else {
			if(!$this->link = mysql_connect($dbhost, $dbuser, $dbpw)) {
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') {
			if($dbcharset) {
				mysql_query("SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') {
				mysql_query("SET sql_mode=''", $this->link);
			}
		}

		if($dbname) {
			mysql_select_db($dbname, $this->link);
		}
		mysql_query('SET NAMES '.$dbcharset,$this->link);

	}

	/**
	 *public
	 * */
	function result_first($sql) {
		$queryresult = $this->query($sql);
		return $this->result($queryresult, 0);
	}

	/**
	 * 返回的是二维数组的形式，比较容易使用，首选
	 * 返回sql查询的结果，如果id不写，返回的数组的index按照从0开始的递增
	 * 如果id写查询的列的id，那么index按照实际的id作为index
	 * */
	function fetch_all($sql, $id = '') {
		$arr = array();
		$queryresult = $this->query($sql);
		while($data = $this->fetch_array($queryresult)) {
			!empty($id) ? $arr[$data[$id]] = $data : $arr[] = $data;
		}
		return $arr;
	}

	/**
	 * 从fetch_all的结果中抽取一个某column组成的一维数组
	 * @param $two_dim_arr fetchTwoDimArray的返回值
	 * @param $column_name 要抽取的column名称
	 * @return 一维数组，由指定column的值组成
	 */
	function fetchArrayFromTwoDim($two_dim_arr,$column_name){
		$arr=array();
		foreach($two_dim_arr as $key=>$con){
			$con=array_change_key_case($con,CASE_LOWER);
			$arr[]=$con[$column_name];
		}
		return $arr;
	}

	/**
	 * 对于insert、update、delete的sql语句是public的
	 * 对于select的sql语句是private的，如果select，应该选择fetch_all fetch_first这两个方法
	 * 默认查询方法mysql_query()
	 * 如果要求使用UNBUFFERED的查询方式，并且mysql_unbuffered_query的查询函数已经定义，那么使用mysql_unbuffered_query()，否则使用mysql_query()
	 * */
	function query($sql, $type = '', $cachetime = FALSE) {
		$func = ($type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query')) ? 'mysql_unbuffered_query' : 'mysql_query';
		//如果查询有结果或者不是silent方法，都不会显示错误
		if(!($queryresult = $func($sql, $this->link)) && $type != 'SILENT') {
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		$this->histories[] = $sql;//在sql查询历史中添加一个元素
		return $queryresult;
	}
	/**
	 * private
	 * 从query后得到的结果集中取得一行作为关联数组，或数字数组，或二者兼有
	 * 通过query得到一个array，array默认通过键名得到值
	 * */
	function fetch_array($queryresult, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array($queryresult, $result_type);
	}
	/**
	 * 一般为private
	 * 从query后查询的结果集中取得一行作为数字数组
	 * */
	function fetch_row($queryresult) {
		$queryresult = mysql_fetch_row($queryresult);//从结果集中取得一行作为枚举数组
		return $queryresult;
	}


	function cache_gc() {
		$this->query("DELETE FROM {$this->tablepre}sqlcaches WHERE expiry<$this->time");
	}


	/**
	 * 返回结果集中行的数目。此命令仅对 SELECT 语句有效
	 * */
	function num_rows($queryresult) {
		$queryresult = mysql_num_rows($queryresult);
		return $queryresult;
	}
	/**
	 *  INSERT，UPDATE 或者 DELETE 查询所影响到的行的数目
	 * */
	function affected_rows() {
		return mysql_affected_rows($this->link);
	}
	/**
	 *
	 * 取得结果集中字段的数目,一般只对select语句有效
	 * */
	function num_fields($queryresult) {
		return mysql_num_fields($queryresult);
	}

	function error() {
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() {
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}
	/**
	 * 得到某查询结果中的一行
	 * 推荐使用高性能的替代函数：mysql_fetch_row()，mysql_fetch_array()，mysql_fetch_assoc() 和 mysql_fetch_object()
	 * */
	function result($queryresult, $row) {
		$queryresult = @mysql_result($queryresult, $row);
		return $queryresult;
	}
	/**
	 * public
	 * 将释放所有与结果标识符 result 所关联的内存
	 * 仅需要在考虑到返回很大的结果集时会占用多少内存时调用。在脚本结束后所有关联的内存都会被自动释放
	 * */
	function free_result($queryresult) {
		return mysql_free_result($queryresult);
	}
	/**
	 * public
	 * 取得上一步 INSERT 操作产生的 ID
	 * */
	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}
	/**
	 *
	 * */
	function fetch_fields($queryresult) {
		return mysql_fetch_field($queryresult);
	}

	function version() {
		return mysql_get_server_info($this->link);
	}

	function close() {
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') {
		$error = mysql_error();
		$errorno = mysql_errno();
		if($errorno == 2006 && $this->goneaway-- > 0) {
			$this->connect($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname, $this->dbcharset, $this->pconnect, $this->tablepre, $this->time);
			$this->query($sql);
		} else {
			$s = '';
			if($message) {
				$s = "<b>Baituan info:</b> $message<br />";
			}
			if($sql) {
				$s .= '<b>SQL:</b>'.htmlspecialchars($sql).'<br />';
			}
			$s .= '<b>Error:</b>'.$error.'<br />';
			$s .= '<b>Errno:</b>'.$errorno.'<br />';
			exit($s);
		}
	}
}
?>