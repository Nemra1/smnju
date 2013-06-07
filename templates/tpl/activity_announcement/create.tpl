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
					delay_redirect('/activity_announcement/edit/' + r['info']);
                },
                wtxt: '创建中……',
            }
            
            process_form(options);
            
            return false;
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/activity_announcement/create/" method="post">
    <div>
        <label>
            标题
        </label>
        <input type="text" name="name" />
    </div>
	    <div>
        <label>
            简介
        </label>
        <input id="activity_create_announcement_desc_editor" class="ck_editor" name="desc" />
    </div>
    <div>
        <label>
            内容
        </label>
        <input id="activity_create_announcement_content_editor" class="ck_editor" name="content" />
    </div>
    <div>
        <select name="activity_id">
        	<option value="-1">不指定</option>
            {html_options options=$activity_options}
        </select>
    </div>
    <div>
        <a href="#" id="main_commit">创建</a>
    </div>
</form>
