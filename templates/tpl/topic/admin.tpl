{include file="topic/admin_header.tpl"}
<div>
    <a href="{$siteurl}/topic/create">+创建主题</a>
</div>
<div>
    <div class="widget , max_width">
        <div class="widget_content">
            <table class="stock , center">
                <tr>
                    <th>
                        名称
                    </th>
                    <th>
                        创建时间
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                {foreach from=$topics item=topic}
                <tr>
                    <td>
                        <a href="{$siteurl}/topic/edit/{$topic->id}">{$topic->name} </a>
                    </td>
                    <td>
                        {$topic->create_time}
                    </td>
                    <td>
                        <span><a href="{$siteurl}/topic/{$topic->id}" target="_blank">查看</a>
						{if $topic->is_delete eq 'no'}
						<a href="#" num="{$topic->id}" class="delete_topic">删除</a>
						{else}
						<a href="#" num="{$topic->id}" class="restore_topic">恢复</a>
						{/if}
						<a href="{$siteurl}/discussion/admin/{$topic->id}">帖子管理</a>
						</span>
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
        $('.delete_topic').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/topic/delete/' + id);
            return false;
        });
        
        $('.restore_topic').click(function(){
            var id = $(this).attr('num');
            process_restore_action(site_url + '/topic/restore/' + id);
            return false;
        });
    });
</script>
{/literal}