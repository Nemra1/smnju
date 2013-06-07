<?php
if (!defined('S_ROOT')) {
	define('S_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}
session_start();

require_once S_ROOT . './config.php';
require_once S_ROOT . './source/lib/function_common.php';
require_once S_ROOT . './source/lib/table_action.php';
require_once S_ROOT . './source/controller/controller.class.php';


ini_set('date.timezone', 'Asia/Shanghai');

$_SGLOBAL = array();
$_SGLOBAL['debug_log'] = S_ROOT . "./log/notice.log";

ini_set("error_reporting", E_ALL);
ini_set("display_errors", 0);
ini_set("error_log", $_SGLOBAL['debug_log']);
ini_set("log_errors", 1);

$siteurl = $_SC['siteurl'];

preprocess_get_post();

require_once S_ROOT . './source/enum_type.php';
require_once S_ROOT . './source/lib/html_helper.php';
require_once S_ROOT.'./source/lib/pagination.class.php';
require_once S_ROOT.'./source/lib/helper.php';
ini_set("error_reporting", E_ERROR);
require_once S_ROOT . "./cometchat/cometchat_init.php";
ini_set("error_reporting", E_ALL);
db_connect();
//获取当前用户的信息
check_authority();



// set path to Smarty directory
define("SMARTY_DIR", S_ROOT . "smarty/libs/");

require_once(SMARTY_DIR . "Smarty.class.php");
$_SGLOBAL['smarty'] = new Smarty;
$_SGLOBAL['smarty']->template_dir = S_ROOT . 'templates/tpl';
$_SGLOBAL['smarty']->compile_dir = S_ROOT . 'smarty/templates_c';
$_SGLOBAL['smarty']->config_dir = S_ROOT . 'smarty/configs';
$_SGLOBAL['smarty']->caching = false;
$_SGLOBAL['smarty']->cache_lifetime = 120;
$_SGLOBAL['smarty']->debugging = false;
$_SGLOBAL['smarty']->assign('siteurl', $_SC['siteurl']);
$_SGLOBAL['smarty']->assign('img_site_url', $_SC['img_site_url']);
$_SGLOBAL['smarty']->assign('user_id', array_key_exists('user_id', $_SESSION) ? $_SESSION['user_id'] : null);
$_SGLOBAL['smarty']->assign('admin', is_admin() ? 1 : 0);
$_SGLOBAL['smarty']->assign('user_name', array_key_exists('user_name', $_SESSION) ? $_SESSION['user_name'] : 0);
$_SGLOBAL['smarty']->assign('alias_name', array_key_exists('alias_name', $_SESSION) ? $_SESSION['alias_name'] : null);
$_SGLOBAL['smarty']->assign('refresh', 0);
$_SGLOBAL['smarty']->assign('is_index_page',0);

if (!empty($_SESSION['notice'])) {
	$_SGLOBAL['notice'] = $_SESSION['notice'];
	unset($_SESSION['notice']);
}
spl_autoload_register("__my_autoload");
?>
