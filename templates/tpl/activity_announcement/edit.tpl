{include file="activity_announcement/admin_header.tpl"}
<script type="text/javascript" src="{$siteurl}/templates/ckeditor/ckeditor.js">
</script>
{literal}
<script type="text/javascript">
    $(function(){
        $('#main_commit').click(function(){
            var options = {
                not_empty: ['name', 'content', 'desc'],
                success: function(r){
                    show_message_box('创建成功');
                },
                wtxt: '创建中……'
            }
            
            process_form(options);
            
            return false;
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/activity_announcement/edit/{$activity_announcement->id}" method="post">
    <div>
        <label>
            活动名称
        </label>
        <input type="text" name="name" value='{$activity_announcement->name}'/>
    </div>
    <div>
        <label>
            简介
        </label>
        <textarea id="activity_edit_desc_editor" name="desc" class="ck_editor">
            {$activity_announcement->desc}
        </textarea>
    </div>
    <div>
        <label>
            内容
        </label>
        <textarea id="activity_edit_content_editor" name="content" class="ck_editor">
            {$activity_announcement->content}
        </textarea>
    </div>
    <div>
        <label>
            所属活动
        </label>
        <select name="activity_id">
            {html_options options=$activities_options selected=$activity_announcement->activity_id}
        </select>
    </div>
    <div>
        <a href="#" id="main_commit">修改</a>
    </div>
</form>
