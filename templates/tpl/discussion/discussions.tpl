<div class='page_top'>
{$page_catalog}
</div>
{foreach from=$discussions item=discussion}
<div class="discussion">
    <h3 class="title-discussion">
        <strong>
            <a href="{$discussion->show_url}">
                {$discussion->name}
            </a>
        </strong>

        <div class="pub-type">
			<span class="timestamp">
                {$discussion->create_time_str}
            </span>
        </div>
    </h3>
    <div class="text-discussion">
        {$discussion->brief_content}
    </div>
    <div class="full_listfoot">

    </div>
    <p class="stat-discussion bottom-margin">
        <a href="{$discussion->show_url}">阅读</a>({$discussion->read_count})
        <span class="pipe">|</span>
        <a href="{$discussion->show_url}#comments">评论</a>({$discussion->comment_count})
    </p>
</div>
{/foreach}
<div class='page_bottom'>
{$page_catalog}
</div>