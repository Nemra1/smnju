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
                    show_message_box('修改成功。');
                },
                wtxt: '修改中……'
            };
            
            
            process_form(options);
            return false;
        });
        
    })
</script>
<script type="text/javascript">
    $(function(){
        var due_year = $('#due_year').attr('val');
        var due_month = $('#due_month').attr('val');
        var due_day = $('#due_day').attr('val');
        var ds = new DateSelector("due_year", "due_month", "due_day", {
            MaxYear: new Date().getFullYear() + 1,
            MinYear: new Date().getFullYear(),
            unselected: 0,
            Year: due_year,
            Month: due_month,
            Day: due_day
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/topic/edit/{$topic->id}" method="post">
    <div>
        <label>
            标题
        </label>
        <input type="text" name="name" value='{$topic->name}'/>
    </div>
    <div>
        <label>
            简介
        </label>
        <textarea id="activity_edit_desc_editor" name="desc" class="ck_editor">
            {$topic->desc}
        </textarea>
    </div>
    <div>
        <label>
            内容
        </label>
        <textarea id="activity_edit_content_editor" name="content" class="ck_editor">
            {$topic->content}
        </textarea>
    </div>
    <div>
        <label>
            截止日期 
        </label>
        <select id="due_year" val="{$topic->due_year}">
        </select>
        <select id="due_month" val="{$topic->due_month}">
        </select>
        <select id="due_day" val="{$topic->due_day}">
        </select>
    </div>
    <div>
        <a href="#" id="main_commit">修改</a>
    </div>
</form>
