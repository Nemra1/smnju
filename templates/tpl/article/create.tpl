<script type="text/javascript" src='{$siteurl}/templates/ckeditor/ckeditor.js'>
</script>
{literal}
<script type="text/javascript">
	$(function(){
		$("#main_commit").click(function(){
			var options={
				not_empty:['title','content','topic_id'],
				success:function(r){
					show_message_box('创建成功');
					delay_redirect('/article/'+r['info']);
				}
			};
			
			process_form(options);
			
			return false;
		});
	})

</script>
{/literal}
<form action="{$siteurl}/article/create/" id="main_form">
	<input type="hidden" name="topic_id" value="{$topic->id}"/>
    <h3><a href="{$siteurl}/topic/{$topic->id}" target="_blank">{$topic->name}</a></h3>
    <div>
        <label>
            题目:
        </label>
        <input type="text" name="title" />
    </div>
    <div>
        <label>
            内容:
        </label>
        <textarea name="content" class="ck_editor" id='article_content_editor'>
        </textarea>
    </div>
	<div>
    <a id='main_commit' href="#">
        创建文章
    </a>
	</div>
</form>
