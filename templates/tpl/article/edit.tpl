<script type="text/javascript" src='{$siteurl}/templates/ckeditor/ckeditor.js'></script>
{literal}
<script type="text/javascript">
    $(function(){
        $("#main_commit").click(function(){
            var options={
                not_empty:['title','content'],
                success:function(r){
                    show_message_box('更新成功');
                    delay_redirect('/article/'+r['info']);
                }
            };

            process_form(options);

            return false;
        });
    })

</script>
{/literal}

<form action="{$siteurl}/article/edit/{$article->id}" id="main_form">
    <h3><a href="{$siteurl}/topic/{$topic->id}" target="_blank">{$topic->name}</a></h3>
    <div>
        <label>
            题目:
        </label>
        <input type="text" name="title" value="{$article->title}" />
    </div>
    <div>
        <label>
            内容:
        </label>
        <textarea name="content" class="ck_editor" id='article_content_editor'>{$article->content}</textarea>
    </div>
    <div>
        <a id='main_commit' href="#">
            更新文章
        </a>
    </div>
</form>
