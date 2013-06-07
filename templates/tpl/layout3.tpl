<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        {if $refresh}
        <meta http-equiv="refresh" content="3; url={$refresh_url}">
        {/if}<title>思目人</title>
        <link href="/templates/css/reset.css" rel="stylesheet" type="text/css" />
        <link href="/templates/css/style.css" rel="stylesheet" type="text/css" />
        <link href="/templates/css/layout.css" rel="stylesheet" type="text/css" />
        <!--[if lt IE 7]>
            <link rel="stylesheet" href="/templates/css/lt-ie7.css" type="text/css" media="screen" />
        <![endif]-->
        <!--[if lte IE 7]>
            <link rel="stylesheet" href="/templates/css/lte-ie7.css" type="text/css" media="screen" />
        <![endif]--><script type="text/javascript" src="/templates/js/jquery-1.5.2.min.js" />
        </script>
    </head>
    <body id="{$page_id}_page">
        <div id="container">
            <div class="fix_height" id="header">
                <h1>思目人</h1>
                <div id="user_status">
                    {if $user_id}
                    <p>
                        天天向上，<a href="/user/">{$user_name}</a>
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
            <div class="fix_height" id="guide">
                <ul>
                    <li>
                        <a href="/">首页</a>
                    </li>
                    <li>
                        <a href="/user/">个人主页</a>
                    </li>
                    <li>
                        <a href="/discussion">主题讨论区</a>
                    </li>
                    <li>
                        <a href="/activity/">校园活动总汇</a>
                    </li>
                    <li>
                        <a href="/simu">思目基金</a>
                    </li>
                    <li>
                        <a class="lastmenu" href="/public/">公示</a>
                    </li>
                    <li>
                        <a class="lastmenu" href="/complaint">问题与投诉</a>
                    </li>
                </ul>
            </div>
            <div id="message">
                {$message}
            </div>
            <div id="content">
                {include file=$content_tpl}
            </div>
            <div id="footer">
                <p>
                    <a href="#">合作伙伴</a>
                    - <a href="#">关于我们 </a>
                    - <a href="#">联系我们</a>
                </p>
                <p>
                    © 2011 all rights reserved.   -   京ICP备xxxxxx号   京公网安备xxxxxxx号
                </p>
            </div>
            <script type="text/javascript" src="/templates/ckeditor/ckeditor.js">
            </script>
            <script type="text/javascript" src="/templates/js/global.js">
            </script>
        </div>
    </body>
</html>
