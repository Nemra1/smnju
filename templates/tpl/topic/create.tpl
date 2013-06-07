{include file="activity_announcement/admin_header.tpl"}
<script type="text/javascript" src="{$siteurl}/templates/js/date_select.js">
</script>
<script type="text/javascript" src="{$siteurl}/templates/ckeditor/ckeditor.js">
</script>
{literal}
<script type="text/javascript">
    $(function(){
    
        $('#main_commit').click(function(){
            var options = {
                not_empty: ['name', 'content', 'desc'],
                transform: function(data){
                    if ($("#due_year").val() != '-1' && $('#due_month').val() != '-1' && $('#due_day').val() != '-1') {
                        var due_str = $("#due_year").val() + '-' + $('#due_month').val() + '-' + $('#due_day').val();
                        data['due_time'] = due_str;
                    }
                },
                success: function(r){
                    show_message_box('创建成功。');
					delay_redirect('/topic/edit/' + r['info']);
                   
                },
				wtxt:'创建中……'
            };
            
            
            process_form(options);
            return false;
        });
    })
</script>
<script type="text/javascript">
    $(function(){
    
        var ds = new DateSelector("due_year", "due_month", "due_day", {
            MaxYear: new Date().getFullYear() + 1,
            MinYear: new Date().getFullYear(),
            unselected: 1
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/topic/create/" method="post">
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
        <input id="topic_create_desc_editor" class="ck_editor" name="desc" />
    </div>
    <div>
        <label>
            内容
        </label>
        <input id="topic_create_content_editor" class="ck_editor" name="content" />
    </div>
    <div>
        <label>
            截止日期 
        </label>
        <select id="due_year">
        </select>
        <select id="due_month">
        </select>
        <select id="due_day">
        </select>
    </div>
    <div>
        <a href="#" id="main_commit">创建</a>
    </div>
</form>