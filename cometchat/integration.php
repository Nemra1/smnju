<?php

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'config.php');
/* ADVANCED */
session_start();



define('SET_SESSION_NAME', ''); // Session name
define('DO_NOT_START_SESSION', '1'); // Set to 1 if you have already started the session
define('DO_NOT_DESTROY_SESSION', '0'); // Set to 1 if you do not want to destroy session on logout
define('SWITCH_ENABLED', '1');
define('INCLUDE_JQUERY', '1');
define('FORCE_MAGIC_QUOTES', '0');
define('ADD_LAST_ACTIVITY', '1');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* DATABASE */
include_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'cometchat/JSON.php';


// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW
// DO NOT EDIT DATABASE VALUES BELOW

define('DB_SERVER', $_SC['dbhost']);
define('DB_PORT', '3306');
define('DB_USERNAME', $_SC['dbuser']);
define('DB_PASSWORD', $_SC['dbpw']);
define('DB_NAME', $_SC['dbname']);
define('TABLE_PREFIX', $_SC['tablepre']);
define('DB_USERTABLE', 'user');
define('DB_USERTABLE_NAME', 'alias_name');
define('DB_USERTABLE_USERID', 'id');
define('DB_USERTABLE_LASTACTIVITY', 'lastactivity');

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getUserID()
{
	$user_id = 0;

	if (!empty($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
	}

	return $user_id;
}

function getFriendsList($userid, $time)
{
	$sql = ("select DISTINCT user.id userid, user.name username, user.lastactivity lastactivity,little_avatar avatar, user.id link,
cometchat_status.message, cometchat_status.status
from user left join cometchat_status on user.id = cometchat_status.userid
where user.id <> '" . mysql_real_escape_string($userid) . "' and ('" . $time . "'-user.lastactivity <120)
order by username asc");
	return $sql;
}


function getUserDetails($userid)
{
	$sql = ("select user.id userid, user.name username, user.lastactivity lastactivity,  user.name link, avatar , cometchat_status.message, cometchat_status.status from user left join cometchat_status on user.id = cometchat_status.userid where user.id = '" . mysql_real_escape_string($userid) . "'");
	return $sql;
}

function updateLastActivity($userid)
{
	$sql = ("update `" . TABLE_PREFIX . DB_USERTABLE . "` set " . DB_USERTABLE_LASTACTIVITY . " = '" . getTimeStamp() . "' where " . DB_USERTABLE_USERID . " = '" . mysql_real_escape_string($userid) . "'");
	return $sql;
}

function getUserStatus($userid)
{
	$sql = ("select " . TABLE_PREFIX . DB_USERTABLE . ".id, cometchat_status.status from " . TABLE_PREFIX . DB_USERTABLE . " left join cometchat_status on " . TABLE_PREFIX . DB_USERTABLE . ".id = cometchat_status.userid where " . TABLE_PREFIX . DB_USERTABLE . ".id = '" . mysql_real_escape_string($userid) . "'");
	return $sql;
}

function getLink($link)
{
	global $_SC;
	return  $_SC['siteurl'] ."/user/". $link;
}

function getAvatar($image){
	if (is_file(COMET_ROOT. "../upload/avatar/" . $image)) {
		return BASE_URL."../upload/avatar/". $image;
	} else {
		return BASE_URL . "../upload/avatar/default.gif";
	}
}


function getTimeStamp()
{
	return time();
}

function processTime($time)
{
	return $time;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* HOOKS */

function hooks_statusupdate($userid, $statusmessage)
{
	$sql = ("update " . TABLE_PREFIX . DB_USERTABLE . " set status = '" . mysql_real_escape_string($statusmessage) . "', status_date = '" . getTimeStamp() . "' where id = '" . mysql_real_escape_string($userid) . "'");
	$query = mysql_query($sql);
}

function hooks_forcefriends()
{

}

function hooks_activityupdate($userid, $status)
{

}

function hooks_message($userid, $unsanitizedmessage)
{

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/* LICENSE */
/* Nulled by TrioxX */

$p_ = 4;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>