<div id="topic">
	<div id="topic_name">
		<h2 class="title">
		<a href="{$siteurl}/discussion/topic_show/{$topic->id}">
			{$topic->name}
		</a>
		</h2>
	</div>
	<div id='topic_article_count' class="simple_list">
		<p class="info">该主题下已经有{$article_count}篇投稿</p>
	</div>
	<!--<div id="topic_content_portion" class="article">
		<span>{$topic->content}</span>
	</div>-->
</div>
<p>
	该主题下的所有文章
</p>
<div id="article_list">
{foreach from=$articles item=article}
<div class="article">
	<div class="article_title">
		<a href="{$siteurl}/article/show/{$article->id}">{$article->title}</a>
		
	</div>
	<div class="aritcle_content_portion">
		{$article->portion_content}
	</div>
</div>
{/foreach}
</div>
