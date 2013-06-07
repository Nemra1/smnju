{literal}
<script type="text/javascript">
    $(function(){
        $('#main_commit').click(function(){
        
            var options = {
                not_empty: ['student_number', 'password'],
                success: function(r){
                    show_message_box('登陆成功!跳转至个人主页。');
                    delay_redirect('/user/show/');
                },
                wtxt: '登录中',
            };
            process_form(options);
            return false;
        });
    })
</script>
{/literal}
<form id="main_form" action="{$siteurl}/login/" method="post">
    <div class="widget , max_width">
        <h2 class="water5">账户登录</h2>
        <div class="widget_content">
            <div class="table , dialog" style="margin-top: 150px;">
                <table>
                    <tr>
                        <th>
                            学&nbsp;&nbsp;号 ：
                        </th>
                        <td>
                            <input type="text" name="student_number"/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            密&nbsp;&nbsp;码 ：
                        </th>
                        <td>
                            <input type="password" name="password"/>
                        </td>
                    </tr>
                    <tr>
                        <th>
                        </th>
                        <td>
                            <a class="submit" href="#" id="main_commit">登录</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
