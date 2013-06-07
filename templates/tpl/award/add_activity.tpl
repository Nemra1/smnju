{include file="award/admin_header.tpl"}
{literal}
<script type="text/javascript">
    $(function(){
        $('#main_commit').click(function(){
            var options = {
                not_empty: ['name', 'activity_id'],
                success: function(r){
                    show_message_box('添加成功');
                    delay_redirect('/award/edit_activity/' + r['info']);
                }
            };
            
            process_form(options);
            
            return false;
        });
    })
</script>
{/literal}
<form id="main_form" method="post" action="{$siteurl}/award/add_activity">
    <div>
        <label>
            名称
        </label>
        <input type="text" name="name"/>
    </div>
    <div>
        <label>
            所属活动
        </label>
        <select name="activity_id">
            {html_options options=$activity_options}
        </select>
    </div>
    <div>
        <a href="#" id="main_commit">创建</a>
    </div>
</form>
