$(function(){
    $('#login_commit').click(function(){
        var options = {
            not_empty: ['student_number', 'password'],
            success: function(r){
                show_message_box('登陆成功!跳转至个人主页。');
                delay_redirect('/user/show/');
            },
            wtxt: '登录中',
            form: 'login_form'
        };
        process_form(options);
        return false;
    });
    
    function init_login_form(){
        $("#login_form").each(function(){
            $('#student_number').click(function(){
                if ($(this).val() == '学号') 
                    $(this).val('');
            }).blur(function(){
                if ($(this).val() == '') 
                    $(this).val('学号');
            });
            
            $('#password').click(function(){
                if ($(this).val() == '密码') {
                    $(this).after('<input id="password" name="password" type="password"/>').remove();
                    $("#password").focus();
                    init_login_form();
                }
            }).focus(function(){
                if ($(this).val() == '密码') {
                    $(this).after('<input id="password" name="password" type="password"/>').remove();
                    $("#password").focus();
                    init_login_form();
                }
            }).blur(function(){
                if ($(this).val() == '') {
                    $(this).after('<input id="password" name="password" type="text" value="密码"/>').remove();
                    init_login_form();
                }
            });
        });
    }
    
    init_login_form();
    
    
    
})
