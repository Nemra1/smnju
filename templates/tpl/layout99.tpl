<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        {if $refresh}
        <meta http-equiv="refresh" content="3;url={$siteurl}{$refresh_url}">
        {/if}<title>思目首页</title>
        <link href="{$siteurl}/templates/css/reset.css" rel="stylesheet" type="text/css" />
        <link href="{$siteurl}/templates/css/layout.css" rel="stylesheet" type="text/css" />
        <link href="{$siteurl}/templates/css/style.css" rel="stylesheet" type="text/css" />
        <link href="{$siteurl}/templates/css/page.css" rel="stylesheet" type="text/css" />
        <link href="{$siteurl}/templates/css/index.css" rel="stylesheet" type="text/css" />
        <link href="{$siteurl}/templates/css/index_common.css" rel="stylesheet" type="text/css" />
        <!--[if lt IE 7]>
            <link rel="stylesheet" href="{$siteurl}/templates/css/lt-ie7.css" type="text/css" media="screen" />
        <![endif]-->
        <!--[if lte IE 7]>
            <link rel="stylesheet" href="{$siteurl}/templates/css/lte-ie7.css" type="text/css" media="screen" />
        <![endif]-->
        <link href="{$siteurl}/templates/css/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/templates/js/jquery-1.6.2.min.js" />
        </script>
        <script type="text/javascript">
            var site_url = "{$siteurl}";
        </script>
    </head>
    <body id="{$page_id}_page">
        <div id="top_nav">
            {if $user_id}<span>天天向上，<a href="/user/show">{$user_name}</a><a href="/logout/">登出</a></span>
            {else}<span><a href="/login/">登录</a><a href="/register/">注册</a></span>
            {/if}
        </div>
        <div id="container">
            <div id="main">
                <div id="navg">
                    <img id="img_logo" src="{$siteurl}/web/img/logo.png" alt="" />
                    <div id="uni_about">
                        <h1><a href="http://www.nju.edu.cn">南京大学</a></h1>
                        <h2><a href="#">思目奖学金</a></h2>
                        <h3><a href="#">www.smnju.com</a></h3>
                    </div>
                    <div id="navgLink">
                        <div id="img_nju_logo">
                            <img src="{$siteurl}/web/img/nju_logo.png" alt="" />
                        </div>
                        <div>
                            <ul>
                                <li>
                                    <a href="{$siteurl}/">首页</a>
                                </li>
                                <li>
                                    <a href="{$siteurl}/user/show">个人主页</a>
                                </li>
                                <li>
                                    <a href="{$siteurl}/topic">主题讨论区</a>
                                </li>
                                <li>
                                    <a href="{$siteurl}/activity">校园活动总汇</a>
                                </li>
                                <li>
                                    <a href="{$siteurl}/simu">思目基金</a>
                                </li>
                                <li>
                                    <a href="{$siteurl}/site_announcement">公告</a>
                                </li>
                                <li>
                                    <a href="{$siteurl}/qa">问题与投诉</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="content_container">
                    {include file=$content_tpl}
                </div>
            </div>
        </div>
        <div id="coinfo">
            <p>
                |联系我们	|
            </p>
            <p>
                Copyright@思目奖学金
            </p>
        </div>
        <div id="message_box">
        </div>
    </body>
    <script type="text/javascript" src="{$siteurl}/templates/js/global.js">
    </script>
    <script type="text/javascript" src="{$siteurl}/templates/js/jquery-ui-1.8.16.custom.dialog.min.js">
    </script>
</html>
<!--
<body id="{$page_id}_page">
<div id="container">
<div id="banner_header" class="fix_height">
<div id="user_status">
{if $user_id}
<p>
天天向上，<a href="/user/show">{$user_name}</a>
<a href="/logout/">登出</a>
</p>
{else}
<p>
<a href="/login/">登录</a>
<a href="/register/">注册</a>
</p>
{/if}
</div>
</div>
<div class="fix_height">
<div id="nav">
<ul>
<li>
<a href="{$siteurl}/">首页</a>
</li>
{if $user_id}
<li>
<a href="{$siteurl}/user/show">个人主页</a>
</li>
{/if}
<li>
<a href="{$siteurl}/topic">主题讨论区</a>
</li>
<li>
<a href="{$siteurl}/activity">校园活动总汇</a>
</li>
<li>
<a href="{$siteurl}/simu">思目基金</a>
</li>
<li>
<a href="{$siteurl}/site_announcement">公告</a>
</li>
<li>
<a href="{$siteurl}/qa">问题与投诉</a>
</li>
</ul>
<div id="nav_bottom">
</div>
</div>
<div id="content">
</div>
</div>
<div id="footer">
</div>
<div id="message_box">
</div>
</div>
<script type="text/javascript" src="{$siteurl}/templates/js/global.js">
</script>
<script type="text/javascript" src="{$siteurl}/templates/js/jquery-ui-1.8.16.custom.dialog.min.js">
</script>
</body>
</html>
-->
