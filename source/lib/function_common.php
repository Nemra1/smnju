<?php

/**
 * 根据session和cookie，检查用户的权限，是访客、普通用户还是管理员，并且把判断结果放入$_SGLOBAL['role']中
 * 本函数将关于用户的信息写道SESSION中
 * 包括：
 * user_id
 * user_name
 * role
 * alias_name
 * admin
 * 如果
 */
function check_authority()
{
	global $_SGLOBAL, $_SESSION, $_COOKIE;
	//首选session判断用户角色
	//这个条件在上个条件不满足的情况下一般不会发生，但是为了避免其他代码的以来，仍然保留
	if (!isset($_SESSION['user_id'])) {
		$_SESSION['role']='guest';
	}

	// //如果不存在有效的session中的user_id变量，那么看cookie中是否存在关于user的信息
	// if (! empty_in_array ( 'user_id', $_COOKIE )) {
	// $user_id = $_COOKIE ['user_id'];
	// $session_cookie_user_code = $_COOKIE ['SCUC'];
	// $result = get_element ( 'user', '*', "id='$user_id' and session_cookie_user_code='$session_cookie_user_code'" );
	// $result = $result [0];
	// if (empty ( $result )) { //如果不能查到该用户，则为访客
	// $_SGLOBAL ['role'] = ROLE_GUEST;
	// } else{//否则把用户信息放入session
	// $_SESSION['user_name']=$result['name'];
	// $_SESSION['user_id']=$result['id'];
	// $_SESSION['role']=$result['role'];
	// $_SESSION['alias_name']=$result['alias_name'];
	// if($_SESSION['role']==ROLE_ADMINISTRATOR){
	// $_SESSION['admin']=1;
	// }
	// }
	// return;
	// }

}

/**
 * 生成定长随机字符串
 * @param  $n 要生存字符串的长度
 */
function randcode($n)
{
	$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$re = '';
	$len = strlen($str);
	for ($i = 0; $i < $n; $i++) {
		$re .= $str{rand(0, $len - 1)};
	}
	return $re;
}

function write_log($log)
{
	static $fp=null;
	global $_SGLOBAL;
	if($fp==null){
		$fp = fopen($_SGLOBAL['debug_log'], 'a+');
	}
	if (is_array($log) || is_object($log)) {
		$log = json_encode($log);
	}
	$debug_arr = debug_backtrace();
	$debug_arr = $debug_arr[0];

	$str = "[" . date("d-M-Y H:i:s") . "]" . "\t" . $log . "\t" . $debug_arr['file'] . "\t" . $debug_arr['line'] . "\r\n";

	fwrite($fp, $str);
}

function show_message($info, $url = 0)
{
	if ($url == -1) {
		if(!empty($_SERVER['HTTP_REFERER']))
		$url = $_SERVER['HTTP_REFERER'];
		else
		$url='/';
	}
	if (empty($url)) {
		register_smarty(array('content_tpl' => 'my/show_message.tpl', 'message' => $info));
	} else
	register_smarty(array('content_tpl' => 'my/show_message.tpl', 'message' => $info, 'refresh_url' => $url, 'refresh' => '1'));
}

function days_ago($time, $n)
{
	return $time - 3600 * 24 * $n;
}

//SQL ADDSLASHES
function saddslashes($string)
{
	if (is_array($string)) {
		foreach ($string as $key => $val) {
			$string[$key] = saddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

//取消HTML代码
function shtmlspecialchars($string)
{
	if (is_array($string)) {
		foreach ($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

function logged_in()
{
	return !empty($_SESSION['user_id']);
}

function is_admin()
{
	return isset($_SESSION['role'])?$_SESSION['role']==='admin':false;
}


function makethumb($imgpath, $dstW, $dstDir)
{


	$srcFile = $imgpath;
	$dstFileName = time() . rand(0, 1000) . '.' . $dstW . '.thumb.jpg';
	$dstFile = $dstDir . $dstFileName;

	$data = GetImageSize($srcFile);
	switch ($data [2]) {
		case 1 :
			$im = @ImageCreateFromGIF($srcFile);
			break;
		case 2 :
			$im = @ImageCreateFromJPEG($srcFile);
			break;
		case 3 :
			$im = @ImageCreateFromPNG($srcFile);
			imagesavealpha($im, true);
			break;
	}
	if (!$im)
	return False;
	$srcW = ImageSX($im);
	$srcH = ImageSY($im);

	$dstH = $srcH * $dstW / $srcW;

	$ni = ImageCreateTrueColor($dstW, $dstH);
	$dstX = 0;
	$dstY = 0;

	$white = ImageColorAllocate($ni, 255, 255, 255);
	$black = ImageColorAllocate($ni, 0, 0, 0);
	imagefilledrectangle($ni, 0, 0, $dstW, $dstH, $white);
	// 填充背景色
	ImageCopyResized($ni, $im, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);

	imagepng($ni, $dstFile, 9);
	imagedestroy($im);
	imagedestroy($ni);
	return $dstFileName;
}

function makethumb_by_height($imgpath, $dstH, $dstDir)
{
	$srcFile = $imgpath;
	$dstFileName = time() . rand(0, 1000) . '.' . $dstH . '.thumb.jpg';
	$dstFile = $dstDir . $dstFileName;

	$data = GetImageSize($srcFile);
	switch ($data [2]) {
		case 1 :
			$im = @ImageCreateFromGIF($srcFile);
			break;
		case 2 :
			$im = @ImageCreateFromJPEG($srcFile);
			break;
		case 3 :
			$im = @ImageCreateFromPNG($srcFile);
			break;
		case 6 :
			$im = @imagecreatefromwbmp($srcFile);
			break;
	}
	if (!$im)
	return False;
	$srcW = ImageSX($im);
	$srcH = ImageSY($im);

	$dstW = $srcW * $dstH / $srcH;

	$ni = ImageCreateTrueColor($dstW, $dstH);
	$dstX = 0;
	$dstY = 0;

	$white = ImageColorAllocate($ni, 255, 255, 255);
	$black = ImageColorAllocate($ni, 0, 0, 0);
	imagefilledrectangle($ni, 0, 0, $dstW, $dstH, $white);
	// 填充背景色
	ImageCopyResized($ni, $im, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);

	imagepng($ni, $dstFile, 9);
	imagedestroy($im);
	imagedestroy($ni);
	return $dstFileName;
}

/**
 *
 * @param $name
 * @param $dir
 * @return str正常  -1后缀名错误 -2太大 -3没复制成功
 */
function uploadimg($name, $dir,$extension=array('bmp','jpg','gif','png'))
{
	$config = array();
	$config['img'] = $extension;
	//img允许后缀
	$config['img_size'] = 5;
	//上传img大小上限 单位：MB
	$config['name'] = time() . "" . mt_rand(10, 100000);
	//上传后的文件命名规则 这里以unix时间戳来命名
	if (is_uploaded_file($_FILES[$name]['tmp_name'])) {
		//判断上传文件是否允许
		$filearr = pathinfo($_FILES[$name]['name']);
		$filetype = $filearr["extension"];
		if (!in_array(strtolower($filetype), $config["img"]))
		return -1;

		//判断文件大小是否符合要求
		if ($_FILES[$name]['size'] > $config["img_size"] * 1024 * 1024)
		return -2;
		$file_rela = $dir . $config['name'] . "." . $filetype;
		$file_host = $file_rela;

		if (move_uploaded_file($_FILES[$name]['tmp_name'], $file_host)) {
			return $config['name'] . "." . $filetype;
		} else {
			//复制失败了
			return -3;
		}
	}
	return 0;
}

/**
 * 根据模板和注册的数组，获取字符串
 */
function fetch_smarty($array, $tpl_page)
{
	global $_SGLOBAL, $page_id, $controller, $method;
	if (!is_array($array))
	return;
	foreach ($array as $k => $v) {
		$_SGLOBAL['smarty']->assign($k, $v);
	}
	$_SGLOBAL['smarty']->assign('_SGLOBAL', $_SGLOBAL);

	if (!isset($array['page_id'])) {
		$_SGLOBAL['smarty']->assign('page_id', $page_id);
	}
	if (!isset($array['content_tpl'])) {
		$_SGLOBAL['smarty']->assign('content_tpl', $controller . '/' . $method . '.tpl');

	}
	$ret = $_SGLOBAL['smarty']->fetch($tpl_page);
	return $ret;
}

function register_smarty($array = array(), $default_page = 'layout.tpl')
{
	global $_SGLOBAL, $page_id, $controller, $method;
	if (!is_array($array))
	return;
	foreach ($array as $k => $v) {
		$_SGLOBAL['smarty']->assign($k, $v);
	}
	$_SGLOBAL['smarty']->assign('_SGLOBAL', $_SGLOBAL);

	if (!isset($array['page_id'])) {
		$_SGLOBAL['smarty']->assign('page_id', $page_id);
	}
	if (!isset($array['content_tpl'])) {
		$_SGLOBAL['smarty']->assign('content_tpl', $controller . '/' . $method . '.tpl');

	}
	$_SGLOBAL['smarty']->display($default_page);
	exit();
}

function array_trim($array)
{
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			$array[$k] = array_trim($v);
		}
	} elseif (is_string($array)) {
		return trim($array);
	}
	return $array;
}

function redirect_page($url)
{
	global $siteurl;
	header('location:' . $siteurl . $url);
	exit();
}

function check_uppercase($char)
{
	if ($char <= 'Z' && $char >= 'A') {
		return true;
	}
	return false;
}

function upper_case_every_word($str, $split = '_')
{
	$str = explode($split, $str);
	$strArr = sucfirst($str);
	$str = implode('', $strArr);
	return $str;
}

function sucfirst($arr)
{
	if (is_array($arr)) {
		foreach ($arr as &$value) {
			$value = sucfirst($value);
		}
	} elseif (is_string($arr)) {
		return ucfirst($arr);
	}
	return $arr;
}

function get_formated_time($timeInt)
{
	if(is_int($timeInt)||is_numeric($timeInt)){
		return date('Y-m-d H:i:s', $timeInt);
	}
}

function is_post()
{
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function class2tb_name($class)
{
	$array = str_split($class);
	$ret = '';
	$first = true;
	foreach ($array as $c) {
		if ($c >= 'A' && $c <= 'Z') {
			$ret .= (!$first ? '_' : '') . strtolower($c);
			$first = false;
		} else {
			$ret .= $c;
		}
	}
	return $ret;
}

function sexecute_func(&$array, $func, $para = array(), $result = 0)
{
	if (is_object($array)) {
		return call_user_func_array(array($array, $func), $para);
	} elseif (is_array($array)) {
		foreach ($array as $k => $a) {
			if ($result)
			$array[$k] = sexecute_func($array[$k], $func, $para, $result);
			else {
				sexecute_func($array[$k], $func, $para, $result);
			}
		}
	} else {
		return call_user_func($func, $para);
	}
}

function __my_autoload($class_name)
{
	$class_name = class2tb_name($class_name);
	$path = S_ROOT . './source/model/' . $class_name . '.class.php';
	if (file_exists($path)) {
		include_once $path;
	}
}

function preprocess_get_post()
{

	$magic_quote = get_magic_quotes_gpc();
	if (empty($magic_quote)) {
		$_GET = saddslashes($_GET);
		$_POST = saddslashes($_POST);
	}


	$_GET = array_trim($_GET);
	$_POST = array_trim($_POST);

	foreach($_GET as $k=>$v){
		if($v===''){
			unset($_GET[$k]);
		}
	}

	foreach($_POST as $k=>$v){
		if($v===''){
			unset($_POST[$k]);
		}
	}
}



//
///**
// *
// * @param $type  JS_RESPONSE_ALERT为执行alert("$content") 命令 ,JS_RESPONSE_REDIRECT为执行页面跳转到 $site_url.$content页面,
// *               JS_RESPONSE_CALLBACK直接调用回调函数
// * @param $content
// * @return
// */
//function server_response($type,$content=null){
//
//	global $siteurl;
//	switch($type){
//		case JS_RESPONSE_ALERT:{
//			$main="show_form_message_box('{$content}');";
//			break;
//		}
//		case JS_RESPONSE_REDIRECT:{
//			$main="location.href='{$siteurl}{$content}';";
//			break;
//		}
//		case JS_RESPONSE_CALLBACK:{
//			$js_callback='';
//			$js_callback=empty($_REQUEST['js_callback'])?$js_callback:$_REQUEST['js_callback'];
//			$js_callback=empty($_SERVER['HTTP_JS_CALLBACK'])?$js_callback:$_SERVER['HTTP_JS_CALLBACK'];
//
//			if(empty($js_callback))
//			exit('-1：not found js_call_back');
//			else{
//				$func=$js_callback;
//			}
//			$main="callback.$func('$content')";
//			break;
//		}
//		case JS_RESPONSE_NOTHING:{
//			exit('');
//			break;
//		}
//		case JS_RESPONSE_RELOAD:{
//			exit('location.reload();');
//			break;
//		}
//		case JS_RESPONSE_TEXT:{
//			exit($content);
//		}
//
//	}
//
//	exit($main);
//}

function server_response($code, $info = '')
{
	$result = array('code' => $code, 'info' => $info);
	$json = json_encode($result);
	die($json);
}

function in_ajax()
{
	return array_key_exists('HTTP_REQUEST_TYPE',$_SERVER)?($_SERVER["HTTP_REQUEST_TYPE"] == 'ajax'):false;
}

function site_url($path)
{
	global $siteurl;
	return $siteurl . $path;
}

////////////////////////////////////////////////////////
// Function:         dump
// Inspired from:     PHP.net Contributions
// Description: Helps with php debugging

function dump(&$var, $info = FALSE)
{
	$scope = false;
	$prefix = 'unique';
	$suffix = 'value';

	if ($scope)
	$vals = $scope;
	else
	$vals = $GLOBALS;

	$old = $var;
	$var = $new = $prefix . rand() . $suffix;
	$vname = FALSE;
	foreach ($vals as $key => $val)
	if ($val === $new)
	$vname = $key;
	$var = $old;

	echo "<pre style='margin: 0px 0px 10px 0px; display: block; background: white; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 10px; line-height: 13px;'>";
	if ($info != FALSE)
	echo "<b style='color: red;'>$info:</b><br>";
	do_dump($var, '$' . $vname);
	echo "</pre>";
}

////////////////////////////////////////////////////////
// Function:         do_dump
// Inspired from:     PHP.net Contributions
// Description: Better GI than print_r or var_dump

function do_dump(&$var, $var_name = NULL, $indent = NULL, $reference = NULL)
{
	$do_dump_indent = "<span style='color:#eeeeee;'>|</span> &nbsp;&nbsp; ";
	$reference = $reference . $var_name;
	$keyvar = 'the_do_dump_recursion_protection_scheme';
	$keyname = 'referenced_object_name';

	if (is_array($var) && isset($var[$keyvar])) {
		$real_var = &$var[$keyvar];
		$real_name = &$var[$keyname];
		$type = ucfirst(gettype($real_var));
		echo "$indent$var_name <span style='color:#a2a2a2'>$type</span> = <span style='color:#e87800;'>&amp;$real_name</span><br>";
	} else {
		$var = array($keyvar => $var, $keyname => $reference);
		$avar = &$var[$keyvar];

		$type = ucfirst(gettype($avar));
		if ($type == "String")
		$type_color = "<span style='color:green'>";
		elseif ($type == "Integer")
		$type_color = "<span style='color:red'>";
		elseif ($type == "Double") {
			$type_color = "<span style='color:#0099c5'>";
			$type = "Float";
		} elseif ($type == "Boolean")
		$type_color = "<span style='color:#92008d'>";
		elseif ($type == "NULL")
		$type_color = "<span style='color:black'>";

		if (is_array($avar)) {
			$count = count($avar);
			echo "$indent" . ($var_name ? "$var_name => " : "") . "<span style='color:#a2a2a2'>$type ($count)</span><br>$indent(<br>";
			$keys = array_keys($avar);
			foreach ($keys as $name) {
				$value = &$avar[$name];
				do_dump($value, "['$name']", $indent . $do_dump_indent, $reference);
			}
			echo "$indent)<br>";
		} elseif (is_object($avar)) {
			echo "$indent$var_name <span style='color:#a2a2a2'>$type</span><br>$indent(<br>";
			foreach ($avar as $name => $value)
			do_dump($value, "$name", $indent . $do_dump_indent, $reference);
			echo "$indent)<br>";
		} elseif (is_int($avar))
		echo "$indent$var_name = <span style='color:#a2a2a2'>$type(" . strlen($avar) . ")</span> $type_color$avar</span><br>";
		elseif (is_string($avar))
		echo "$indent$var_name = <span style='color:#a2a2a2'>$type(" . strlen($avar) . ")</span> $type_color\"$avar\"</span><br>";
		elseif (is_float($avar))
		echo "$indent$var_name = <span style='color:#a2a2a2'>$type(" . strlen($avar) . ")</span> $type_color$avar</span><br>";
		elseif (is_bool($avar))
		echo "$indent$var_name = <span style='color:#a2a2a2'>$type(" . strlen($avar) . ")</span> $type_color" . ($avar == 1 ? "TRUE" : "FALSE") . "</span><br>";
		elseif (is_null($avar))
		echo "$indent$var_name = <span style='color:#a2a2a2'>$type(" . strlen($avar) . ")</span> {$type_color}NULL</span><br>";
		else
		echo "$indent$var_name = <span style='color:#a2a2a2'>$type(" . strlen($avar) . ")</span> $avar<br>";

		$var = $var[$keyvar];
	}
}

function echo_object_error($object, $msg)
{
	if (!is_object($object))
	server_response(-1, $msg);

	$error = $object->error_msg();
	server_response(-1, empty($error) ? $msg : $error);
}

/******************************************************************
 * PHP截取UTF-8字符串，解决半字符问题。
 * 英文、数字（半角）为1字节（8位），中文（全角）为3字节
 * @return 取出的字符串, 当$len小于等于0时, 会返回整个字符串
 * @param $str 源字符串
 * $len 左边的子串的长度
 ****************************************************************/
function utf_substr($str, $len)
{
	for ($i = 0; $i < $len; $i++) {
		$temp_str = substr($str, 0, 1);
		if (ord($temp_str) > 127) {
			$i++;
			if ($i < $len) {
				$new_str[] = substr($str, 0, 3);
				$str = substr($str, 3);
			}
		} else {
			$new_str[] = substr($str, 0, 1);
			$str = substr($str, 1);
		}
	}
	return implode('', $new_str);
}

/**
 * 去掉所有的HTML标记和JavaScript标记
 */

function trim_html_js($document)
{
	$document = trim($document);

	if (strlen($document) <= 0) {
		return $document;
	}

	// $document 应包含一个 HTML 文档。
	// 本例将去掉 HTML 标记，javascript 代码
	// 和空白字符。还会将一些通用的
	// HTML 实体转换成相应的文本。

	$search = array("'<script[^>]*?>.*?</script>'si", // 去掉 javascript
        "'<[\/\!]*?[^<>]*?>'si", // 去掉 HTML 标记
        "'([\r\n])[\s]+'", // 去掉空白字符
        "'&(quot|#34);'i", // 替换 HTML 实体
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&#(\d+);'e"); // 作为 PHP 代码运行

	$replace = array("",
        "",
        "\\1",
        "\"",
        "&",
        "<",
        ">",
        " ",
	chr(161),
	chr(162),
	chr(163),
	chr(169),
        "chr(\\1)");

	return preg_replace($search, $replace, $document);
}

function get_interval_time($interval_seconds)
{
	$ret_val = 0;
	$minutes = floor($interval_seconds / 60);
	$hours = floor($minutes / 60);
	$days = floor($hours / 24);
	$months = floor($days / 30);
	$years = floor($days / 365);
	if ($years != 0) {
		return $years . "年前";
	}
	if ($months != 0) {
		return $months . '个月前';
	}
	if ($days != 0) {
		return $days . '天前';
	}
	if ($hours != 0) {
		return $hours . '小时前';
	}
	if ($minutes != 0) {
		return $minutes . '分钟前';
	}
	return "刚刚";
}



function format_json($str){
	$str=str_replace(array("\n","\r","\t"),'',$str);
	return $str;
}


function get_html_top_content(){

}

?>