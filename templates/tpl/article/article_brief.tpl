<div class="article">
	<h3 class="title-article">
		<span class="editarticle"> 
			<a href="{$article->edit_url}">编辑</a> | 
			<a onclick="confirmDelete(this,720997480);return false;" href="{$article->delete_url}">删除</a>
		</span>
		<strong>
			<a href="{$article->show_url}">
				{$article->title}
			</a> 
		</strong>
		<div class="pub-type">
			<span class="timestamp">
				{$article->create_time}
			</span>
			<span class="group">
				(话题:<a href="{$topic->show_url}">{$topic->name}</a>)
			</span>
		</div>
	</h3>
	<div class="text-article">
		{$article->brief_content}
	</div>
	<div class="full_listfoot">
		
	</div>
	<p class="stat-article bottom-margin">
		<a href="{$article->show_url}">阅读</a>({$article->read_count})
		<span class="pipe">|</span>
		<a href="{$article->show_url}#comments">评论</a>({$article->comment_count})
	</p>
</div>
