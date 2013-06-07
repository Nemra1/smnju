<style>
    #user_header ul li {
        /*float: left;
        width: 100px;*/
    }
</style>
<div id="user_header" class="fix_height white_nav blocktop blockbottom content_center"><!-- class:shadow -->
    {if $person_same}
    <ul>
    	<li><a href="{$siteurl}/user/show/">个人首页</a></li>
    	<li><a href="{$siteurl}/user/edit/">修改信息</a></li>
    	<li><a href="{$siteurl}/change_password/">修改密码</a></li>
    	<li><a href="{$siteurl}/user/avatar">上传头像</a></li>
    	<li><a href="{$siteurl}/user/privacy/">隐私设置</a></li>
    </ul>
    {/if}
</div>
