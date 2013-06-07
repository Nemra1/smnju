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
                    show_message_box('注册成功，进入个人主页。');
                    delay_redirect("/user/show");
                },
            };
            
            process_form(options);
			
			return false;
   
        });
    });
</script>
<form id="main_form" action="{$siteurl}/user/privacy_register/" method="post">
    <div>
        <label>
            公开姓名
        </label>
        <input name="name" type="checkbox" checked/>
    </div>
    <div>
        <label>
            公开院系
        </label>
        <input type="checkbox" name="institute" checked/>
    </div>
    <div>
        <label>
            公开性别
        </label>
        <input name="gender" type="checkbox" checked/>
    </div>
    <div>
        <label>
            公开手机
        </label>
        <input name="telnum" type="checkbox" />
    </div>
    <div>
        <label>
            公开邮箱
        </label>
        <input name="email" type="checkbox" />
    </div>
    <div>
        <label>
            公开年级
        </label>
        <input name="grade" type="checkbox" checked/>
    </div>
    <div>
        <a href="#" id="main_commit">提交</a>
        <a href="{$siteurl}/user/show/" id="privacy_register_skip">跳过（完成注册）</a>
    </div>
</form>
