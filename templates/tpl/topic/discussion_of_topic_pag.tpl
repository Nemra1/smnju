{foreach from=$discussions item=discussion}
<div class="discussion">
    <h3 class="title-discussion">
        <strong>
            <a href="{$siteurl}/discussion/{$discussion->id}">
                {if $discussion->is_top eq yes}
                    <span class='top'>[置顶]</span>
                {/if}
                {$discussion->name}
            </a>
        </strong>

        <div>
            {$discussion->desc}
        </div>

        <div class="pub-type">
			<span class="timestamp">
                {$discussion->create_time}
            </span>
        </div>
    </h3>
    <div class="full_listfoot">

    </div>
    <p class="stat-discussion bottom-margin">
        <a href="{$siteurl}/discussion/{$discussion->id}">阅读</a>({$discussion->read_count})
        <span class="pipe">|</span>
        <a href="{$siteurl}/discussion/{$discussion->id}#comments">评论</a>({$discussion->comment_count})
    </p>
</div>
{/foreach}
<div class='page_bottom'>
{$pag_catlog}
</div>