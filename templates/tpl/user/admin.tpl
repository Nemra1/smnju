{include file="user/admin_header.tpl"}
<div id="user_list">
    <form id="admin_search_user_form" action="{$siteurl}/user/admin_search/" method="get">
    	<div class="content_center">
	        <input type="text" name="search_user" />
	        <select name="search_type">
	            <option value="name">姓名</option>
	            <option value="student_number">学号</option>
	            <option value="id">id号</option>
	        </select>
	        <input type="submit" value="搜索">
	        </input>
        </div>
    </form>
    
    <div class="widget , max_width">
        <div class="widget_content">
            <table class="stock , center">
    <tr>
        <th>
            学号
        </th>
        <th>
            姓名
        </th>
        <th>
            院系
        </th>
        <th>
            操作
        </th>
    </tr>
    {foreach from=$users item=user}
    <tr>
        <td>
            {$user->student_number}
        </td>
        <td>
            {$user->name}
        </td>
        <td>
            {if $user->institute}
            {$user->institute}
            {/if}
        </td>
        <td>
            <a href="{$siteurl}/user/admin_single/{$user->id}">管理</a>
			<a href="{$siteurl}/user/{$user->id}" target="_blank">查看</a>
        </td>
    </tr>
    {/foreach}
			</table>
        </div>
    </div>
</div>
