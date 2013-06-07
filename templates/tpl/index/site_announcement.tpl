<div class="widget max_width">
<div class="widget_content">
<style>
	.topic .right{
		padding-top: 1px;
	}
</style>
<ol>
	{foreach from=$sas item=sa}
	<li>
		<div class='topic'>
		<h3> 
			<a style="color:#4477aa;" href="{$siteurl}/site_announcement/{$sa->id}" >{$sa->name}</a>
			<span class="right">创建时间：{$sa->create_time}</span>
		</h3>
        </div>
	</li>
	{/foreach}
</ol>
</div>
</div>