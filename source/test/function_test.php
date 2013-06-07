<?php
include '../../common.php';
include '../dao/comment.php';
$res=get_comments(3,-1);
dump($res);
?>
