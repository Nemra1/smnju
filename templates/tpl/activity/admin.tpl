{include file="activity/admin_header.tpl"}
<div>
    <a href="{$siteurl}/activity/create/">+创建活动</a>
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
                        操作
                    </th>
                </tr>
                {foreach from=$activities item=activity}
                <tr>
                    <td>
                        <a href="{$siteurl}/activity/edit/{$activity->id}">{$activity->name} </a>
                    </td>
                    <td>
                        <a href="{$siteurl}/activity/{$activity->id}" target="_blank">查看</a>
                        {if $activity->is_delete eq 'no'}<a href="#" num="{$activity->id}" class="delete_activity">删除</a>
                        {else}<a href="#" num="{$activity->id}" class="restore_activity">恢复</a>
                        {/if}
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
        $('.delete_activity').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/activity/delete/' + id);
        });
        
        $('.restore_activity').click(function(){
            var id = $(this).attr('num');
            process_restore_action(site_url + '/activity/restore/' + id);
        });
    });
</script>
{/literal}