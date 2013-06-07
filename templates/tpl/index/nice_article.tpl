<div class="widget max_width">
<div class="widget_content">
<style>
	.topic .right{
		padding-top: 1px;
	}
</style>
<ol>
	{foreach from=$articles item=article}
	<li>
		<div class='topic'>
		<h3> 
			<a style="color:#aa4444;" href="{$siteurl}/site_announcement/{$article->id}" >{$article->title}</a>
			<span class="right">创建时间：{$article->create_time}</span>
		</h3>
        </div>
	</li>
	{/foreach}
</ol>
</div>
</div>