{include file="user/header.tpl"}

<div class="content_center max_width widget center">
    <script type="text/javascript">
        $(function(){
            $('#main_commit').click(function(){
            
                var options = {
                    not_empty: ['alias_name', 'name'],
                    success: function(r){
                        show_message_box('修改成功');
                    },
                    wtxt: '修改中……'
                };
                process_form(options);
                return false;
            });
        });
    </script>
    <form id="main_form" action="{$siteurl}/user/edit" method="post">
        <table class="table , center">
            <tr>
                <th>
                    昵&nbsp;&nbsp;&nbsp;&nbsp;称
                </th>
                <td>
                    <input name="alias_name" type="text" value="{$user->alias_name}"/>
                </td>
            </tr>
            <tr>
                <th>
                    姓&nbsp;&nbsp;&nbsp;&nbsp;名
                </th>
                <td>
                    <input name="name" type="text" value="{$user->name}"/>
                </td>
            </tr>
            <tr>
                <th>
                    手&nbsp;&nbsp;&nbsp;&nbsp;机
                </th>
                <td>
                    <input name="telnum" type="text" value="{$user->telnum}"/>
                </td>
            </tr>
            <tr>
                <th>
                    个人简介
                </th>
                <td>
                    <textarea name="description">
                        {$user->description}
                    </textarea>
                </td>
            </tr>
            <tr>
                <th>
                    邮&nbsp;&nbsp;&nbsp;&nbsp;箱
                </th>
                <td>
                    <input name="email" type="text" value="{$user->email}" />
                </td>
            </tr>
            <tr>
                <th>
                    性&nbsp;&nbsp;&nbsp;&nbsp;别
                </th>
                <td>
                    <select name="gender" class="nowidth">
                        <option value="-1" selected>未指定</option>
                        <option value="0">男</option>
                        <option value="1">女</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    院系
                </th>
                <td>
                    <select name="institute">
                        {html_options values=$_SGLOBAL.helper.html_options.institute.values selected='-1' output=$_SGLOBAL.helper.html_options.institute.names}
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    年&nbsp;&nbsp;&nbsp;&nbsp;级
                </th>
                <td>
                    <select name="grade" class="nowidth">
                        {html_options values=$_SGLOBAL.helper.html_options.grade.values selected='-1' output=$_SGLOBAL.helper.html_options.grade.names}
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                </th>
                <td>
                    <a href="#" class="submit" id="main_commit">提交</a>
                </td>
            </tr>
        </table>
    </form>
</div>
