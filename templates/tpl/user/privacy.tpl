{include file="user/header.tpl"}
<div class="content_center max_width widget center">
    {literal}
    <script type="text/javascript">
        $(function(){
            $('#main_commit').click(function(){
                var options = {
                    transform: function(data){
                        $("#main_form :checkbox").each(function(){
                            var name = $(this).attr('name');
                            var value = $(this).attr('checked');
                            if (value) 
                                data[name] = '1';
                            else 
                                data[name] = '0';
                        });
                    },
                    success: function(r){
                        show_message_box('修改成功！');
                    }
                };
                
                process_form(options);
				
				return false;
        
            });
        });
    </script>
    {/literal}
    <form id="main_form" action="{$siteurl}/user/privacy/" method="post">
        <table class="table , center">
            <tr>
                <th>
                    公开姓名
                </th>
                <td>
                    <input name="name" type="checkbox" {if $user_privacy->name}checked{/if}

/>
                </td>
            </tr>
            <tr>
                <th>
                    公开院系
                </th>
                <td>
                    <input type="checkbox" name="institute" {if $user_privacy->institute}checked{/if}

/>
                </td>
            </tr>
            <tr>
                <th>
                    公开性别
                </th>
                <td>
                    <input name="gender" type="checkbox" {if $user_privacy->gender}checked{/if}

/>
                </td>
            </tr>
            <tr>
                <th>
                    公开手机
                </th>
                <td>
                    <input name="telnum" type="checkbox" {if $user_privacy->telnum}checked{/if}

/>
                </td>
            </tr>
            <tr>
                <th>
                    公开邮箱
                </th>
                <td>
                    <input name="email" type="checkbox" {if $user_privacy->email}checked{/if}

/>
                </td>
            </tr>
            <tr>
                <th>
                    公开年级
                </th>
                <td>
                    <input name="grade" type="checkbox" {if $user_privacy->grade}checked{/if}

/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <a href="#" class="submit" id="main_commit">提交</a>
                </td>
            </tr>
        </table>
    </form>
</div>
