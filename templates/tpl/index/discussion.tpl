<div class="widget max_width">
<div class="widget_content">
<style>
	.topic .right{
		padding-top: 5px;
	}
</style>
<ol>
    {foreach from=$topics item=topic}
    <li>
		<div class='topic'>
		<h3>
            <a style="color:#4444ff;" href="{$siteurl}/discussion/topic_show/{$topic->id}">{$topic->name}</a>
            <span class="right">创建时间：{$topic->create_time}</span>
        </h3>
        </div>
    </li>
    {/foreach}
</ol>
</div>
</div>