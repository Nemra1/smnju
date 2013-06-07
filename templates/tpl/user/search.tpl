{include file="user/admin_header.tpl"}
<div id="user_list">
    <ul>
        {foreach from=$users item=user}
        <li>
            <label>
                学号:{$user.student_number}
            </label>
            <label>
                姓名:{$user.name}
            </label>
            <label>
                院系:{$user.institute}
            </label>
            <a href="/admin/user/{$user.id}">管理此用户</a>
        </li>
        {/foreach}
    </ul>
</div>
