{include file="award/admin_header.tpl"}
{literal}
<script type="text/javascript">
    $(function(){
        $("#main_commit").click(function(){
        
            var options = {
                not_empty: ['name'],
                validate: function(data){
                    if (((data['award_name'] == '') != (data['score'] == '')) && data['user_student_number'] == '') {
                        return false;
                    }
                },
                success: function(r){
                    show_message_box('修改成功');
                    location.reload();
                }
            };
            
            process_form(options);
            
            return false;
        });
        
        $('.delete_award').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/award/delete_activity_award/' + id);
			return false;
        });
        
        
        $('.delete_user_award').click(function(){
            var id = $(this).attr('num');
            process_delete_action(site_url + '/award/delete_user_activity_award/' + id);
			return false;
       
        });
        
        
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/award/edit_activity/{$award_activity->id}/" method="post">
    <div>
        <label>
            名称
        </label>
        <input type="text" name="name" value="{$award_activity->name}" />
    </div>
    <div>
        {foreach from=$awards item=award}
        <div>
            <span>奖项名称：{$award->name}</span>
            <span>分数：{$award->score}</span>
            <a num="{$award->id}" class="delete_award" href="#">删除</a>
        </div>
        {/foreach}
    </div>
    <div>
        <label>
            新加奖项名称
        </label>
        <input type="text" name="award_name" />
        <label>
            分数
        </label>
        <input type="text" name="score" />
    </div>
    <div>
        <label>
            用户学号
        </label>
        <input type="text" name="user_student_number" />
        <label>
            奖项
        </label>
        <select name="activity_award_id" class="nowidth">
            {foreach from=$awards item=award}<option value="{$award->id}">{$award->name}</option>
            {/foreach}
        </select>
    </div>
    <div>
        <a href="#" id="main_commit">提交</a>
    </div>
    <div>
        <ol>
            {foreach from=$award_users item=award_user}
            <li>
                <label>
                    {$award_user.user_name}：
                </label>
                <label>
                    {$award_user.award_name}
                </label>
                <a href="#" class="delete_user_award" num="{$award_user.id}">删除</a>
            </li>
            {/foreach}
        </ol>
    </div>
</form>
