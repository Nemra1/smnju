<link href="{$siteurl}/templates/css/comment.css" rel="stylesheet" type="text/css"/>

<script type='text/javascript' src='{$siteurl}/templates/js/comment.js'></script>
{literal}
<script type="text/javascript">
    $(document).ready(function () {
        register_comment_type('discussion');
    });

</script>
{/literal}
<div id='discussion_id' style='display:none'>{$discussion->id}</div>
<div class="article">
    <h3>{$discussion->name}</h3>
    <span>(主题:<a href="{$siteurl}/topic/{$topic->id}">{$topic->name}</a>)</span>

    <div class="p">{$discussion->content}</div>
</div>

<div id="discussion_comments">
</div>
{*只在是注册用户的时候,评论开放
提交评论时,先把评论的内容上传,然后更新comment内容*}
{if $user_id neq false}
<div id="discussion_comment_add">
</div>
{/if}