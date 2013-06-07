<?php
include '../common.php';
include S_ROOT."source/model/object.php";
include S_ROOT."source/model/user.php";
$u=new BeeUser();
$u->setId(1);
$u->load();
var_dump($u);
?>