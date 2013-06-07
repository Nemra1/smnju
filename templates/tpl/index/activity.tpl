<div class="widget max_width">
<div class="widget_content">
<style>
	.topic .right{
		padding-top: 1px;
	}
</style>
<ol>
   {foreach from=$activity_announcement item=aa}
   <li>
		<div class='topic'>
		<h3> 
       		<a style="color:#226622;" href="{$siteurl}/activity_announcement/{$aa->id}/">{$aa->name}</a>
			<p>{$aa->desc}</p>
			<span>创建时间：{$aa->create_time}</span>
		</h3>
        </div>
	</li>
            {/foreach}
</ol>
</div>
</div>