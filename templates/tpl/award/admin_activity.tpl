<div><a href="{$siteurl}/award/add_activity/">增加一个奖项活动</a></div>
{foreach from=$award_activities item=award_activity}
<a href="{$siteurl}/award/edit_activity/{$award_activity->id}">{$award_activity->name}</a> <a class="delete_award_activity" num="{$activity->id}" href="#">删除</a>
{/foreach}

<script type="text/javascript">
	$(function(){
		$('#delete_award_activity').click(function(){
			var id=$(this).attr('num');
			process_delete_action(site_url+'/award/delete_activity/'+id);
		});
	})
</script>