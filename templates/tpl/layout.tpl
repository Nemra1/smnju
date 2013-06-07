<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        {if $refresh}
        <meta http-equiv="refresh" content="3;url={$siteurl}{$refresh_url}">
        {/if}<title>思目首页</title>
        <link href="{$siteurl}/templates/css2/reset.css" rel="stylesheet" type="text/css"/>
        <link href="{$siteurl}/templates/css2/layout.css" rel="stylesheet" type="text/css"/>
        <link href="{$siteurl}/templates/css2/style.css" rel="stylesheet" type="text/css"/>
        <link href="{$siteurl}/templates/css2/common.css" rel="stylesheet" type="text/css"/>
        <link href="{$siteurl}/templates/css2/page.css" rel="stylesheet" type="text/css"/>
        <!--[if lt IE 7]>
            <link rel="stylesheet" href="{$siteurl}/templates/css2/lt-ie7.css" type="text/css" media="screen"/>
        <![endif]-->
        <!--[if lte IE 7]>
            <link rel="stylesheet" href="{$siteurl}/templates/css2/lte-ie7.css" type="text/css" media="screen"/>
        <![endif]-->
        <link href="{$siteurl}/templates/css2/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="{$siteurl}/templates/js/jquery-1.7.1.min.js">
        </script>
        <script type="text/javascript">
            var site_url = "{$siteurl}";
        </script>
        <link type="text/css" href="{$siteurl}/cometchat/cometchatcss.php" rel="stylesheet" charset="utf-8">
    </head>
    <body id="{$page_id}_page">
        <div id="container">
            <div id="header">
                <div id="head" class="fix_height">
                    <div id="logo_block">
                        <a href="{$siteurl}/"><img id="logo" src="{$siteurl}/templates/img2/simu_logo.png" alt=""/></a>
                    </div>
                    <div id="uni_about">
                        <a href="http://www.nju.edu.cn/"><img src="{$siteurl}/templates/img2/uni_about.png" /></a>
                    </div>
                    <div id="uni_logo">
                        <a href="http://www.nju.edu.cn/"><img src="{$siteurl}/templates/img2/uni_logo.png" /></a>
                    </div>
                </div>
                <div id="nav" class="fix_height">
                    {if $is_index_page}
                    <ul id="nav_list" class="fix_height">
                        <li>
                            <a href="{$siteurl}/">首页</a>
                        </li>
                        <li>
                            <a href="{$siteurl}/simu">思目介绍</a>
                        </li>
                        <li>
                            <a href="{$siteurl}/site_announcement/">公告</a>
                        </li>
                    </ul>
                    {else}
                    <ul id="nav_list" class="fix_height">
                        <li>
                            <a href="{$siteurl}/">首页</a>
                        </li>
                        <li>
                            <a href="{$siteurl}/activity/">校园活动</a>
                        </li>
                        <li>
                            <a href="{$siteurl}/topic/">主题讨论</a>
                        </li>
                        <li>
                            <a href="{$siteurl}/user/">学生风采</a>
                        </li>
                    </ul>
                    {/if}
                </div>
            </div>
            <div id="content_container" class="fix_height">
                {include file=$content_tpl}
            </div>
        </div>
        <div id="footer" class="clear_float">
            <p>
                |联系我们 |
            </p>
            <p>
                Copyright@思目奖学金
            </p>
        </div>
        <div id='message_box'>
        </div>
    </body>
    <script type="text/javascript" src="{$siteurl}/templates/js/layout.js">
    </script>
    <script type="text/javascript" src="{$siteurl}/templates/js/global.js">
    </script>
    <script type="text/javascript" src="{$siteurl}/templates/js/jquery-ui-1.8.16.custom.dialog.min.js">
    </script>
    <script type="text/javascript" src="{$siteurl}/templates/ckeditor/ckeditor.js">
    </script>
    <script type="text/javascript" src="{$siteurl}/cometchat/cometchatjs.php" charset="utf-8">
    </script>
</html>
</body>
</html>
