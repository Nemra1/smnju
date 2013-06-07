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
                    show_message_box('创建成功');
					delay_redirect('/activity/edit/' + r['info']);
                },
                wtxt: '创建中……',
            }
            
            process_form(options);
            
            return false;
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/activity/create/" method="post">
    <div>
        <label>
            活动名称
        </label>
        <input type="text" name="name" />
    </div>
    <div>
        <label>
            简介
        </label>
        <input id="activity_create_desc_editor" class="ck_editor" name="desc" />
    </div>
    <div>
        <label>
            内容
        </label>
        <input id="activity_create_content_editor" class="ck_editor" name="content" />
    </div>
    <div>
        <a href="#" id="main_commit">创建</a>
    </div>
</form>
