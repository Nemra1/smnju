<?php

//////////////////////////////////////
//数据库的各种方法

/**
 * 获取表的每个字段名字
 * @status tested
 */
function get_table_columns_array($table_name) {
	global $_SGLOBAL, $_SC, $_TABLE_COLUMNS;
	static $_TABLE_COLUMNS = array();
	if (isset($_TABLE_COLUMNS[$table_name])) {
		return $_TABLE_COLUMNS[$table_name];
	} else {
		$sql = "SELECT COLUMN_NAME FROM information_schema.`COLUMNS` WHERE TABLE_NAME='" . tname($table_name) . "' AND TABLE_SCHEMA= '$_SC[dbname]'";
		$column_array2d = $_SGLOBAL['db'] -> fetch_all($sql);
		$column_array = $_SGLOBAL['db'] -> fetchArrayFromTwoDim($column_array2d, 'column_name');
		$_TABLE_COLUMNS[$table_name] = $column_array;
		return $column_array;
	}
}

/**
 * 将数据插入数据库
 * @param  $tablename	插入的表名，不含前缀
 * @param  $insertsqlarr	表示一条数据库记录的数组，索引值是column名，对应值
 * @param  $returnid	是返回插入后的主键id
 * @param  $replace	是否是replace，默认是insert。replace和insert唯一不同是可以覆盖primary key相同的记录
 * @param  $silent	是否silent
 * @return id
 */
function inserttable($tablename, $insertsqlarr, $returnid = 1, $replace = false, $silent = 0) {
	global $_SGLOBAL;
	$column_array = get_table_columns_array($tablename);
	$real_content = array();
	foreach ($insertsqlarr as $key => $val) {
		if (in_array($key, $column_array)) {
			$real_content[$key] = $val;
		}
	}
	
	$insertsqlarr = $real_content;
	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma . '`' . $insert_key . '`';
		if ($insert_value === NULL)
		$insertvaluesql .= $comma . 'NULL';
		else
		$insertvaluesql .= $comma . '\'' . $insert_value . '\'';
		$comma = ', ';
	}
	$method = $replace ? 'REPLACE' : 'INSERT';
	//$sql=$method.' INTO '.tname($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')';
	$sql = $method . ' INTO ' . tname($tablename) . ' (' . $insertkeysql . ') VALUES (' . $insertvaluesql . ')';
	$_SGLOBAL['db'] -> query($sql, $silent ? 'SILENT' : '');
	if ($returnid && !$replace) {
		return $_SGLOBAL['db'] -> insert_id();
	}
}

/**
 *
 * 更新已有记录
 * @param  $tablename	表名,不加前缀
 * @param  $setsqlarr	需要更新的column和值的数组
 * @param  $wheresqlarr	update的where语句,指定更新哪一条数据
 * @param  $silent
 */
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent = 0) {
	global $_SGLOBAL;

	if (is_array($setsqlarr)) {
		$column_array = get_table_columns_array($tablename);
		$real_content = array();
		foreach ($setsqlarr as $key => $val) {
			if (in_array($key, $column_array)) {
				$real_content[$key] = $val;
			}
		}
		$setsqlarr = $real_content;
	}

	$setsql = $comma = '';
	if (is_array($setsqlarr)) {
		foreach ($setsqlarr as $set_key => $set_value) {
			if ($set_value !== NULL)
			$setsql .= $comma . '`' . $set_key . '`' . '=\'' . $set_value . '\'';
			else
			$setsql .= $comma . '`' . $set_key . '` =NULL';
			$comma = ', ';
		}
	} elseif (is_string($setsqlarr)) {
		$setsql .= $setsqlarr;
	}
	$where = $comma = '';
	if (empty($wheresqlarr)) {
		$where = '1';
	} elseif (is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			if ($value !== NULL)
			$where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
			else
			$where .= $comma . '`' . $key . '`=NULL';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$sql = 'UPDATE ' . tname($tablename) . ' SET ' . $setsql . ' WHERE ' . $where;

	return $_SGLOBAL['db'] -> query($sql, $silent ? 'SILENT' : '');
}

/**
 * 删除记录
 * @param  $table	表名
 * @param  $conditionsql	删除的where语句
 */

function deletetable($table, $wheresqlarr) {
	global $_SGLOBAL;
	$where = $comma = '';
	if (empty($wheresqlarr)) {
		$where = '1';
	} elseif (is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			if ($value !== NULL)
			$where .= $comma . '`' . $key . '`' . '=\'' . $value . '\'';
			else
			$where .= $comma . '`' . $key . '`=NULL';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}

	$sql = 'delete from ' . tname($table) . ' where ' . $where;
	return $_SGLOBAL['db'] -> query($sql);
}

/**
 * 通过主键键值或where语句获取记录
 * @param  $table	表名
 * @param  $column_name_arr	column的数组，表示查询哪几个column
 * @param  $wheresql	where语句筛选
 * @param  $order_by	根据谁排序，后面可以加入  ASC 或者 DESC
 * @param  $limit	查询几条记录
 * @param  $start	从第几条开始
 * @param  $primary_key_name	主键名,，填入主键名后，返回值的索引是是主键值
 * @param  $ignore_all 如果设为1，则忽略前面所有的设置，用后面的自定义sql语句返回结果
 * @return	二维数组，第一层索引可以是递增数列，如果指定主键，则第一层索引是主键值
 */
function get_element($table, $column_name_arr = 0, $wheresql = 0, $order_by = 0, $limit = 0, $start = 0, $primary_key_name = 0, $ignore_all = 0, $sql = 0) {

	global $_SGLOBAL;
	if ($ignore_all == 0) {//$ignore_all 如果设为1，则忽略前面所有的设置，用后面的自定义sql语句返回结果
		$result = 0;
		//select
		$sql = 'SELECT ';

		if (is_array($column_name_arr)) {
			foreach ($column_name_arr as $con) {
				$sql .= $con . ' ,';
			}
			$sql = rtrim($sql, ',');
		} elseif (empty($column_name_arr)) {
			$sql .= ' * ';
		} else {
			$sql .= ' ' . $column_name_arr . ' ';
		}

		//from
		$sql .= 'FROM ' . tname($table) . ' ';

		//where
		if (!empty($wheresql)) {
			$comma = 'WHERE ';
			if (is_array($wheresql)) {
				foreach ($wheresql as $key => $con) {
					if ($con !== NULL)
					$sql .= $comma . " $key='$con' ";
					else
					$sql .= $comma . " $key=NULL ";

					$comma = " AND ";
				}
			} else {
				$sql .= "$comma $wheresql ";
			}
		}

		//order by
		if (is_array($order_by)) {
			$sql .= ' order by ';
			foreach ($order_by as $column_name => $order) {
				$sql .= ' ' . $column_name . ' ' . $order . ',';
			}
			$sql = trim($sql, ',');
		} else if (is_string($order_by)) {
			$sql .= ' order by ' . $order_by . ' ';
		}

		//limit
		if ($limit != 0 && $start == 0) {
			$sql .= ' limit ' . $limit;
		} else if ($start != 0) {
			$sql .= ' limit ' . $start . ',' . $limit;
		}
	}
	$result = $_SGLOBAL['db'] -> fetch_all($sql, $primary_key_name);

	return $result;
}

/**
 *
 * 获取所有行相应column的数据
 * @param  $table
 * @param  $attr_name_arr
 */
function get_all_element($table, $column_name_arr = 0) {
	return get_element($table, $column_name_arr);
}

/**
 * 根据主键对获取数据
 * @param  $table
 * @param  $key_name
 * @param  $key_value
 * @param  $attr_name_arr
 */
function get_element_by_key($table, $key_name, $key_value, $attr_name_arr = 0) {
	$result = get_element($table, $attr_name_arr, "$key_name='$key_value'", 0, 1, 0);
	if (empty($result))
	return false;
	
	return $result[0];
}

/**
 * 测试某记录是否存在
 * @param  $table	表名
 * @param  $where_arr	测试的where语句或数组
 */
function check_if_exist($table, $where_arr) {
	global $_SGLOBAL;
	$sql = 'SELECT * FROM ' . tname($table) . ' WHERE ';
	if (is_array($where_arr)) {
		$comma = ' ';
		foreach ($where_arr as $key => $val) {
			if ($val !== NULL)
			$sql .= $comma . ' `' . $key . "`='" . $val . "' ";
			else
			$sql .= $comma . ' `' . $key . "`=NULL ";
			$comma = ' AND ';
		};
	} else {
		$sql .= $where_arr;
	}
	$res = $_SGLOBAL['db'] -> query($sql);
	if ($_SGLOBAL['db'] -> num_rows($res) > 0) {
		return true;
	} else {
		return false;
	}
}


  function get_element_count($table_name,$where = 0, $order_by = 0, $limit = 0, $start = 0){
	$ret = get_element($table_name, 'count(*) as c', $where, $order_by, $limit, $start);
	if(empty($ret))
	return false;
	else
	return $ret[0]['c'];
}

/**
 * 连接数据库
 */
function db_connect() {
	global $_SGLOBAL, $_SC;

	require_once (S_ROOT . './source/lib/dbstuff.class.php');

	if (empty($_SGLOBAL['db'])) {
		$_SGLOBAL['db'] = new dbstuff();
		$_SGLOBAL['db'] -> charset = $_SC['dbcharset'];
		$_SGLOBAL['db'] -> connect($_SC['dbhost'], $_SC['dbuser'], $_SC['dbpw'], $_SC['dbname'], $_SC['dbcharset'], $_SC['pconnect']);
		$_SGLOBAL['db'] -> query("SET NAMES UTF8");
	}
}

/**
 * 根据配置文件得到带前缀的表名
 * @param $name
 * @return string
 */
function tname($name) {
	global $_SC;
	return $_SC['tablepre'] . $name;
}

/////////////////////////////////////////////////
?>