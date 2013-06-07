 {$pag_catlog}
{foreach from=$topics item=topic}
<div class='topic'>
    <h2><a href="{$siteurl}/topic/show/{$topic->id}">{$topic->name} </a></h2>
    <div class="text">
        <p>
            {$topic->desc}
        </p>
    </div>
    <p class="stat-topic bottom-margin">
        <a href="{$siteurl}/topic/{$topic->id}">讨论贴</a>({$topic->discussion_count})
        <span class="pipe">|</span>
        <a href="{$siteurl}/topic/{$topic->id}#comments">总评论</a>({$topic->comment_count})
        <div>
		 创建时间：{$topic->create_time}<span class="pipe">|</span>
		 投稿截止时间：{$topic->due_year}-{$topic->due_month}-{$topic->due_day} 23:59:59
		 </div>
	</p>
</div>
{/foreach}
<div class='page_bottom'>
    {$pag_catlog}
</div>
