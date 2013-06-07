{include file="discussion/admin_header.tpl"}
<div>
    <a href="{$siteurl}/topic/create">+创建主题</a>
</div>
<div>
    <div class="widget , max_width">
        <div class="widget_content">
        	<h3>  主题：<a href="{$siteurl}">{$topic->name}</a></h3>
            <table class="stock , center">
                <tr>
                    <th>
                        名称
                    </th>
                    <th>
                        创建时间
                    </th>
                    <th>
                        更新时间
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                {foreach from=$discussions item=discussion}
                <tr>
                    <td>
                        {$discussion->name}
                    </td>
                    <td>
                        {$discussion->create_time}
                    </td>
                    <td>
                        {$discussion->update_time}
                    </td>
                    <td>
                        <span><a href="{$siteurl}/discussion/{$discussion->id}" target="_blank">查看</a> 
						{if $discussion->is_delete eq 'no'}
						<a href="#" num="{$discussion->id}" class="delete_discussion">删除</a> 
						{else}
						<a href="#" num="{$discussion->id}" class="restore_discussion">恢复</a> 
						{/if}</span>
                    </td>
                </tr>
                {/foreach}
            </table>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
    $(function(){
        $('.delete_discussion').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/discussion/delete/' + id);
            return false;
        });
        
        $('.restore_discussion').click(function(){
            var id = $(this).attr('num');
            process_restore_action(site_url + '/discussion/restore/' + id);
            return false;
        });
    });
</script>
{/literal}