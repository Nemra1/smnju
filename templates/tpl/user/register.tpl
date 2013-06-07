{literal}
<script type="text/javascript">
    
    $(function(){
        $('#main_commit').click(function(){
            var options = {
                not_empty: ['name', 'alias_name', 'student_number', 'id_card_number', 'password'],
                validate: function(data){

                    if (data['password'] != data['password2']) {
                        show_message_box('密码不一致！');
                        return false;
                    }
                },
                success: function(r){
                    show_message_box('注册成功，进入下一步。');
                    delay_redirect("/user/avatar_register");
                },
            };
            
            process_form(options);
            
            return false;
        });
    });
</script>
{/literal}
<form id="main_form" action="{$siteurl}/register/" method="post">
    <div class="widget , max_width">
        <h2 class="water4">用户注册</h2>
        <div class="widget_content">
            <table class="table , center">
                <tr>
                    <td>
                    </td>
                    <th>
                        <b class="red">必填项</b>
                    </th>
                </tr>
                <tr>
                    <th>
                        学&nbsp;&nbsp;&nbsp;&nbsp;号
                    </th>
                    <td>
                        <input name="student_number" type="text" />(不公开)
                    </td>
                </tr>
                <tr>
                    <th>
                        姓&nbsp;&nbsp;&nbsp;&nbsp;名 
                    </th>
                    <td>
                        <input name="name" type="text" />(不公开)
                    </td>
                </tr>
                <tr>
                    <th>
                        身&nbsp;份&nbsp;证
                    </th>
                    <td>
                        <input name="id_card_number" type="text" />(不公开)
                    </td>
                </tr>
                <tr>
                    <th>
                        昵&nbsp;&nbsp;&nbsp;&nbsp;称
                    </th>
                    <td>
                        <input name="alias_name" type="text" />
                    </td>
                </tr>
                <tr>
                    <th>
                        密&nbsp;&nbsp;&nbsp;&nbsp;码
                    </th>
                    <td>
                        <input id="password" name="password" type="password" />
                    </td>
                </tr>
                <tr>
                    <th>
                        确认密码
                    </th>
                    <td>
                        <input id="password2" type="password" name="password2" />
                    </td>
                </tr>
                <tr>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <td>
                    </td>
                    <th>
                        <b class="red">选填项</b>
                    </th>
                </tr>
                <tr>
                    <th>
                        院&nbsp;&nbsp;&nbsp;&nbsp;系
                    </th>
                    <td>
                        <select name="institute" class="nowidth">
                            {html_options values=$_SGLOBAL.helper.html_options.institute.values selected='-1' output=$_SGLOBAL.helper.html_options.institute.names}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        性&nbsp;&nbsp;&nbsp;&nbsp;别
                    </th>
                    <td>
                        <select name="gender" class="nowidth">
                            <option value="-1" selected>未指定</option>
                            <option value="1">男</option>
                            <option value="0">女</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        年&nbsp;&nbsp;&nbsp;&nbsp;级
                    </th>
                    <td>
                        <select name="grade" class="nowidth">
                            <option value="-1" selected>未指定</option>
                            <option value="b2008">本科2008级</option>
                            <option value="b2009">本科2009级</option>
                            <option value="b2009">本科2010级</option>
                            <option value="b2009">本科2011级</option>
                            <option value="mf2009">研究生2009级</option>
                            <option value="mf2010">研究生2010级</option>
                            <option value="mf2011">研究生2011级</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        手&nbsp;&nbsp;&nbsp;&nbsp;机
                    </th>
                    <td>
                        <input name="telnum" type="text" />
                    </td>
                </tr>
                <tr>
                    <th>
                        个人简介
                    </th>
                    <td>
                        <textarea name="description">
                        </textarea>
                    </td>
                </tr>
                <tr>
                    <th>
                        邮&nbsp;&nbsp;&nbsp;&nbsp;箱
                    </th>
                    <td>
                        <input name="email" type="text" />
                    </td>
                </tr>
                <tr>
                    <th>
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <th>
                    </th>
                    <td class="content_center">
                        <a id="main_commit" class="submit" href="#">提交</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
