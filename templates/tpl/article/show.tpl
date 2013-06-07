<link href="{$siteurl}/templates/css/article.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src='{$siteurl}/templates/js/comment.js'></script>
<script type="text/javascript" src='{$siteurl}/templates/ckeditor/ckeditor.js'></script>
{literal}
<script type='text/javascript'>

    $(function () {
        var article_id_value = $('#article_id').html();
        register_comment_type('article');

        $('#vote_number').click(function () {
            $.get(site_url + '/article/support_vote/' + $('#article_id').text(), function (data) {
                res = get_ajax_result(data);
                if (res.code == 0) {
                    //可以选择是否让用户注册为用户
                    show_message_box(res.info);
                } else if (res.code == 2) {
                    //告诉用户不能重复投票
                    show_message_box('不能重复投票');
                } else if (res.code == 4) {
                    //把投票数加1
                    $('#vote_number').text(res.info);
                }
            });
            return false;
        });
        $('#article_delete').click(function () {
            $.get(site_url+'/article/delete/'+$('#article_id').text(), function (data) {
                var res_data = get_ajax_result(data);
                if (res_data.code == 1) {
                    show_message_box("文章删除成功")
                }else{
                    show_message_box('文章删除失败')
                }
            });
            return false;
        });
    });
</script>
{/literal}
<div id='topic_name'>
    主题：
    <a href="{$siteurl}/discussion/topic_show/{$topic->id}" target="_blank">{$topic->name}</a>
</div>
<div id='article_id' style='display:none'>{$article->id}</div>
<div id='article_vote'>
    猛击支持：
    <a href="#" id='vote_number'>
    {$article->vote_number}
    </a>
</div>
<div id='article'>
    <div class='article_title'>
    {$article->title}
    </div>
    <div class='article_content'>
    {$article->content}
    </div>
</div>

<div class='article_contribute'>
{if $is_owner eq 1 and $has_been_contributed eq 0 and $has_passed_due_time eq 0}
    <button id='contribute_article_button'>猛击我参加投稿！</button>
    {elseif $has_been_contributed eq 1}
    已投稿
    {if has_been_scored eq 'yes'}
        <a href="#">点击查看结果</a>
    {/if}
    {else}
{/if}
</div>

<div id='operations'>
    <ul>
    {if $is_owner eq 1}
        <li >
            <a id='article_delete' href="#">删除</a>
        </li>
        <li id='article_edit'>
            <a href="{$siteurl}/article/edit/{$article->id}">编辑</a>
        </li>
    {/if}
    </ul>
</div>
<div id='article_comments'>
</div>
{*只在是注册用户的时候,评论开放
提交评论时,先把评论的内容上传,然后更新comment内容*}
{if $user_id neq false}
<div id="article_comment_add">
</div>
{/if}
