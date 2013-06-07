<?php
define('S_ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR);
require S_ROOT . './common.php';

$controller = isset($_GET['a']) ? $_GET['a'] : NULL;
$method = isset($_GET['b']) ? $_GET['b'] : NULL;
$c = isset($_GET['c']) ? $_GET['c'] : NULL;
$d = isset($_GET['d']) ? $_GET['d'] : NULL;

$page_id = $controller . '_' . $method;
$include_controller_file = S_ROOT . "./source/controller/{$controller}_controller.class.php";


if (!file_exists($include_controller_file))
    show_message('url错误', '/');

require_once $include_controller_file;

$class_name = upper_case_every_word($controller, '_');
$class_name = $class_name . 'Controller';
$controller_object = new $class_name;
$controller_object->invoke($method);

?>