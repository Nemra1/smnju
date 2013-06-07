{*
这个模板需要的变量：
comment
comment里要有对应的user对象

*}

<div class="comment_item" id="{$comment->id}">
    <div class="user">
		<span class="avatar">
			<a href="{$siteurl}/user/{$comment->user->id}" target="_blank">
                <img src="{$siteurl}/upload/avatar/{$comment->user->little_avatar}" width="50" height="50"
                     alt="{$comment->user->alias_name}的头像"/>
            </a>
		</span>
		<span class="online_status">
        {if $comment->user->online_status eq 1}
            <img src="{$siteurl}/upload/icons/online.gif" width="13" height="12" alt="online"
                {if $user_id neq $comment->user->id}
                 title="点此和{$comment->user->alias_name}聊天" onclick="jqcc.cometchat.chatWith('{$comment->user->id}');"
                {/if}
                    />
        {/if}
        </span>
		<span class="user_name">
			<a href="{$siteurl}/user/{$comment->user_id}" target="_blank">{$comment->user->alias_name}</a>
		</span>
    </div>
    <div class='comment_info'>
        <span>{$comment->create_time_str}</span>
        <span class="lightText">({$comment->interval_time})</span>
    </div>
    <div class="comment_content">
    {$comment->content}
    </div>
{if $user_id neq false}
    <div class="operations">
        <ul>
            <li><a href="#" class="comment_reply">回复</a>
            </li>
            <li><a href="#" class='cancel_reply' style="display: none">取消</a></li>
            {if $comment->user_id eq $user_id or $admin eq 1}
                <li><a href="#" class="comment_delete">删除</a></li>
            {/if}
        </ul>
    </div>
    <div class="reply_text" style="display: none">
        <textarea class='comment_content' rows="10" cols="50"></textarea>
        <a href="#" class='comment_commit' onclick="">提交</a>
    </div>
{/if}
</div>