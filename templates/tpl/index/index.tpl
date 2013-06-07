<link rel="stylesheet" href="{$siteurl}/templates/css2/index.css"/>
<div id="main_bg">
</div>
<div id="content" class="fix_height">
    <div id="user_panel">
        <div id="user_status">
            {if not $user_id}
            <form id="login_form" action="{$siteurl}/login/">
                <input id="student_number" name="student_number" type="text" value="学号"/><input id="password" name="password" type="text" value="密码"/><a class="login_link" id="login_commit" href="#">登录</a>
                <a class="register_link" href="{$siteurl}/register/">注册</a>
            </form>
            {else}<span>你好，<a href="{$siteurl}/user/show/">{$alias_name}</a><a href="{$siteurl}/logout/">登出</a></span>
            {/if}
        </div>
    </div>
    <div id="news_list">
        <h3><a href="{$siteurl}/">新闻动态</a></h3>
        <div id="news_area">
            {$news_list_content}
        </div>
    </div>
    <div id="func_menu" class="fix_height">
        <h3>站内链接</h3>
        <div class="link_logo">
            <a href="{$siteurl}/topic/"><img src="{$siteurl}/templates/img2/xsxz.png"/>西水小站</a>
        </div>
        <div class="link_logo">
            <a href="{$siteurl}/activity/ "><img src="{$siteurl}/templates/img2/xyhd.png"/>校园活动</a>
        </div>
        <div class="link_logo">
            <a href="{$siteurl}/user/"><img src="{$siteurl}/templates/img2/xsfc.png" />学生风采</a>
        </div>
        <div class="link_logo">
            <a href="{$siteurl}/award/"><img src="{$siteurl}/templates/img2/jj.png" />奖学金</a>
        </div>
    </div>
</div>