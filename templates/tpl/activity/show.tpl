<div class="article">
	<div>
	    <h3>{$activity->name}</h3>
	</div>
	<br />
	<div class="p">
	    {$activity->content}
	</div>
</div>
<div class="notice">
	<h4>相关通告</h4>
	<ol>
		{foreach from=$activity_announcements item=aa}
		<li><a href="{$siteurl}/activity_announcement/{$aa->id}">{$aa->name}</a> <span>{$aa->create_time}</span></li>
		{/foreach}
	</ol>
</div>
