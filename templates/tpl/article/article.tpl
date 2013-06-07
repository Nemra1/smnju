<div class="article">
	<div class="pager-top">
		<span class="right-line"> 
			<a title="{$next_article->title}" href="$next_article->url">较新一篇</a> / 
			<a title="{$prev_article->title}" href="$prev_article->url">较旧一篇</a> 
		</span>
	</div>
	<h3 class="title-article">
		{if $is_owner eq 1}
		<span class="edit"> 
			<a href="$article->edit_url">编辑</a>
			<span class="pipe">|</span>
			<a href="$article->delete_url" onclick="confirmDelete(this);return false;">删除</a>
		</span>
		{/if}
		<strong>{$article->title}</strong>
		<span class="timestamp">{$article->create_time}
			<span class="group">(话题:<a href="{$topic->show_url}">{$topic->name}</a>)</span> 
		</span>
	</h3>
	<div class="text-article" id="blog-content">
		{$article->content}
	</div>
	<p class="stat-article">
		<span class="stat">
			阅读({$article->read_count})
			<span class="pipe">|</span> 
			评论({$article->comment_count})
		</span>
		<a id="support_vote" href="#nogo">
			支持
		</a>
		<span class='support_vote_count'>{$article->vote_number}</span>
	</p>	
</div>