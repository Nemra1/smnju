{include file="activity/admin_header.tpl"}
<script type="text/javascript" src="{$siteurl}/templates/ckeditor/ckeditor.js">
</script>
{literal}
<script type="text/javascript">
    $(function(){
        $('#main_commit').click(function(){
            var options = {
                not_empty: ['name', 'content', 'desc'],
                success: function(r){
                    show_message_box('修改成功');
                },
                wtxt: '修改中……'
            }
            
            process_form(options);
            
            return false;
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/activity/edit/{$activity->id}" method="post">
    <div>
        <label>
            活动名称
        </label>
        <input type="text" name="name" value='{$activity->name}'/>
    </div>
    <div>
        <label>
            简介
        </label>
        <textarea id="activity_edit_desc_editor" name="desc" class="ck_editor">
            {$activity->desc}
        </textarea>
    </div>
    <div>
        <label>
            内容
        </label>
        <textarea id="activity_edit_content_editor" name="content" class="ck_editor">
            {$activity->content}
        </textarea>
    </div>
    <div>
        <a href="#" id="main_commit">修改</a>
    </div>
</form>
