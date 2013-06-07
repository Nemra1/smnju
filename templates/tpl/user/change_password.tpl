{include file="user/header.tpl"}
<div class="content_center max_width widget center">
    <script type="text/javascript">
        $(function(){
            $('#main_commit').click(function(){
            
                var options = {
                    validate: function(data){
                        if ($('#password').val() != $('#password2').val()) {
                            show_message_box('密码不一致');
                            return false;
                        }
                    },
                    not_empty: ['old_password', 'password'],
                    success: function(r){
                        show_message_box('密码重置成功！');
                        delay_redirect('/login');
                    },
                    wtxt: '修改中……',
                };
                
                process_form(options);
                
                return false;
                
            })
        })
    </script>
    <form id="change_password_form" action="{$siteurl}/change_password/" method="post">
        <table class="table , center">
            <tr>
                <th>
                    密&nbsp;&nbsp;&nbsp;&nbsp;码
                </th>
                <td>
                    <input class="require" type="password" id="old_password" name="old_password"/>
                </td>
            </tr>
            <tr>
                <th>
                    新&nbsp;密&nbsp;码
                </th>
                <td>
                    <input class="require" name="password" id="password" type="password" />
                </td>
            </tr>
            <tr>
                <th>
                    确认密码
                </th>
                <td>
                    <input class="require" id="password2" type="password" />
                </td>
            </tr>
            <tr>
                <th>
                </th>
                <td>
                    <a href="#" class="submit" id="change_password_commit">提交</a>
                </td>
            </tr>
        </table>
    </form>
</div>
