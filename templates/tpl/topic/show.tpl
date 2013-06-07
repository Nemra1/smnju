<link href="{$siteurl}/templates/css/discussion.css" rel="stylesheet" type="text/css"/>

<div class="article">
    <div id='topic_id' style='display:none'>{$topic->id}</div>

    <div class='topic_title'>
        <h3 class="title">{$topic->name}</h3>
        <a class="title" href='{$siteurl}/article/list_all/{$topic->id}'>该主题所有文章</a>
    </div>
    
    <div class="fix_height white_nav content_center">
    	<ul>
    		{if $user_id neq false}
    		<li><a href='{$siteurl}/article/create/{$topic->id}' id="contribute_article_button">对该主题投稿</a></li>
    		<li><a href="{$siteurl}/discussion/create?topic_id={$topic->id}">对该主题发起讨论</a></li>
    		{/if}
    	</ul>
    </div>

    <div class='topic_content'>
        <p>{$topic->desc}</p>
    </div>

    <div class="topic_info simple_list">
        <p class="info">
            话题创建时间:{$topic->create_time}
        </p>

        <p class="info">
            投稿截止时间:{$topic->due_year}-{$topic->due_month}-{$topic->due_day} 23:59:59
        </p>
    </div>

</div>
<div id="discussion_list">
{$discussion_list_content}
</div>
