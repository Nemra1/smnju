{include file="site_announcement/admin_header.tpl"}
<div class="widget , max_width">
    <div class="widget_content">
        <table class="stock , center">
            {foreach from=$sas item=sa}
            <tr>
                <td>
                    <a href="{$siteurl}/site_announcement/edit/{$sa->id}">{$sa->name} </a>
                </td>
                <td>
                    <a href="{$siteurl}/site_announcement/{$sa->id}" target="_blank">查看</a>
                    {if $sa->is_delete eq 'no'}
					<a href="#" num="{$sa->id}" class="delete_site_announcement">删除</a>
                    {else}
					<a href="#" num="{$sa->id}" class="restore_site_announcement">恢复</a>
                    {/if}
                </td>
            </tr>
            {/foreach}
        </table>
    </div>
</div>
{literal}
<script type="text/javascript">
    $(function(){
        $('.delete_site_announcement').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/site_announcement/delete/' + id);
            return false;
        });
		
		$('.restore_site_announcement').click(function(){
            var id = $(this).attr('num');
            process_restore_action(site_url + '/site_announcement/restore/' + id);
            return false;
        });
    });
</script>
{/literal}