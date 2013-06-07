{include file="award/admin_header.tpl"}
<div>
    <a href="{$siteurl}/award/add_activity/">+增加一个奖项活动</a>
</div>

    <div class="widget , max_width">
        <div class="widget_content">
            <table class="stock , center">
            
    {foreach from=$award_activities item=award_activity}
    <tr>
    	<td>
        <a href="{$siteurl}/award/edit_activity/{$award_activity->id}">{$award_activity->name}</a>
        </td><td>
        	{if $award_activity->is_delete eq 'no'}
        <a class="delete_award_activity" num="{$award_activity->id}" href="#">删除</a>
        {else}
		<a class="restore_award_activity" num="{$award_activity->id}" href="#">恢复</a>
		{/if}
		</td>
    </tr>{/foreach}
    
    		</table>
        </div>
    </div>

<script type="text/javascript">
    $(function(){
        $('.delete_award_activity').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/award/delete_activity/' + id);
        });
		
		 $('.restore_award_activity').click(function(){
            var id = $(this).attr('num');
            process_restore_action(site_url + '/award/restore_activity/' + id);
        });
    })
</script>
