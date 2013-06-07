{include file="activity_announcement/admin_header.tpl"}
<div>
    <a href="{$siteurl}/activity_announcement/create">+创建活动公告</a>
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
                        所属活动
                    </th>
                    <th>
                        创建时间
                    </th>
                    <th>
                        操作
                    </th>
                </tr>
                {foreach from=$activity_announcements item=aa}
                <tr>
                    <td>
                        <a href="{$siteurl}/activity_announcement/edit/{$aa->id}">{$aa->name} </a>
                    </td>
                    <td>
                        {if $aa->activity_name}<span><a href="{$siteurl}/activity/{$aa->activity_id}">{$aa->activity_name}</a></span>
                        {/if}
                    </td>
                    <td>
                        {$aa->create_time}
                    </td>
                    <td>
                        <span>{if $aa->is_delete eq 'no'}<a href="#" num="{$aa->id}" class="delete_activity_announcement">删除</a> {else}<a href="#" num="{$aa->id}" class="restore_activity_announcement">恢复</a> {/if}</span>
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
        $('.delete_activity_announcement').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/activity_announcement/delete/' + id);
            return false;
        });
        
        $('.restore_activity_announcement').click(function(){
            var id = $(this).attr('num');
            process_restore_action(site_url + '/activity_announcement/restore/' + id);
            return false;
        });
    });
</script>
{/literal}