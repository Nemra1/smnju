{include file="user/admin_header.tpl"}
{literal}
<script type="text/javascript">
    $(function(){
        $("#main_commit").click(function(){
            var options = {
                success: function(){
                    show_message_box('修改成功');
					location.reload();
                }
            };
            process_form(options);
            return false;
        });
    })
</script>
{/literal}
<div id="user_management">
    <form id="main_form" action="{$siteurl}/user/admin_single/{$user->id}/" method="post">
        <a href="{$siteurl}/user/{$user->id}">进入{$user->name}主页</a>
        <p>
            学号:{$user->student_number}
        </p>
        <p>
            姓名:{$user->name}
        </p>
        <p>
            院系:{$user->institute}
        </p>
        <p>
            昵称:{$user->alias_name}
        </p>
        <p>
            性别:{$user->gender}
        </p>
        <p>
            身份证:{$user->id_card_number}
        </p>
        <p>
            手机:{$user->telnum}
        </p>
        <p>
            电子邮件:{$user->email}
        </p>
        <p>
            生日：{$user->birthday}
        </p>
        <p>
            自我介绍：{$user->description}
        </p>
        <p>
            注册时间：{$user->register_time}
        </p>
        {if $user->is_applicant==1}
        <p>
            该用户已申请奖学金<input type="checkbox" name="cancel_applicant" />
            <label>
                取消申请
            </label>
        </p>
        <p>
            总分数：{$user->total_score}
        </p>
        {else}
        <p>
            该用户未申请奖学金：<input type="checkbox" name="be_applicant" />
            <label>
                让其申请
            </label>
        </p>
        {/if}
        <p>
            <label>
                学分绩录入
            </label>
            <input type="text" value="{$user->credit_grade}" name="credit_grade"/>
        </p>
        <p>
            <label>
                关注数修改
            </label>
            <input type="text" name="follow_count" value="{$user->follow_count}"/>
        </p>
        <p>
            {if $user->status}<input type="checkbox" name="delete" />
            <label>
                注销该用户
            </label>
            {else}<input type="checkbox" name="restore" />
            <label>
                恢复该用户
            </label>
            {/if}
        </p>
        <a href="#" id="main_commit">提交</a>
    </form>
</div>
