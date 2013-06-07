<script src="{$siteurl}/templates/js/jquery.form.js" type="text/javascript">
</script>
{literal}
<script type="text/javascript">
    $(function(){
    
        $('#main_commit').click(function(){
        
            $("#main_form").ajaxSubmit({
                success: function(data){
                    var r = get_ajax_result(data);
                    if (r['code'] == 1) {
                        var imgurl = r['info'];
                        $('#avatar_img').attr('src', imgurl);
                        show_message_box('上传成功');
						$("#avatar_register_skip").text('进入下一步');
                    }
                    else {
                        show_message_box(r['info']);
                    }
                }
            });
            show_wait_message_box('上传中……');
            return false;
        });
        
        
    });
</script>
{/literal}
<style>
    #clip_avatar_form {
        display: none;
    }
    
    #show_avatar {
        width: 500px;
    }
</style>
<form id="main_form" action="{$siteurl}/user/avatar_register/" method="post" enctype="multipart/form-data">
    <div>
        <img id="avatar_img" src="{$siteurl}/upload/avatar/{$user->medium_avatar}"/>
    </div>
    <div>
        <label>
            上传头像
        </label>
        <input type="file" name="avatar" />
    </div>
    <div>
        <a href="#" id="main_commit">提交</a>
        <a href="{$siteurl}/user/privacy_register/" id="avatar_register_skip">略过（下一步）</a>
    </div>
</form>
