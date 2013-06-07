<div class="discussion">
    <h3 class="title-discussion">
		<span class="editdiscussion"> 
			<a href="{$discussion->edit_url}">编辑</a> | 
			<a onclick="confirmDelete(this,720997480);return false;" href="{$discussion->delete_url}">删除</a>
		</span>
        <strong>
            <a href="{$discussion->show_url}">
            {$discussion->title}
            </a>
        </strong>

        <div class="pub-type">
			<span class="timestamp">
            {$discussion->create_time}
            </span>
			<span class="group">
				(话题:<a href="{$topic->show_url}">{$topic->name}</a>)
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
