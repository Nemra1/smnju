{literal}
<script type="text/javascript">
    $(function () {
        $("#main_commit").click(function () {
            var options = {
                not_empty:['name', 'content'],
                success:function (r) {
                    show_message_box('创建成功');
                    delay_redirect('/discussion/edit/' + r['info']);
                }
            };
            process_form(options);

            return false;
        });
    })
</script>
{/literal}

<form action="{$siteurl}/discussion/create" id="main_form">
    <label>讨论标题</label>
	<input type="hidden" name="topic_id" value="{$topic->id}"/>
    <input name="name" type="text"/>
    <span>(主题:<a href="{$siteurl}/topic/{$topic->id}">{$topic->name}</a>)</span>
    <textarea name='content' rows="20" cols="80" class="ck_editor" id="discussion_content"></textarea>
    <a href="#" id="main_commit">提交</a>
</form>