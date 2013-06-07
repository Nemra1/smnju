{include file="user/header.tpl"}
<script type="text/javascript" src="{$siteurl}/templates/js/jquery.form.js"></script>
<div class="content_center max_width widget center">
{literal}
<script type="text/javascript">
    $(function(){
        $('#change_avatar_commit').click(function(){
            $("#change_avatar_form").ajaxSubmit({
                success: function(data){
					var r =eval("("+data+")");
					
					if(r['code']==1){
						$("#avatar_img").attr('src',r['info']);
						show_message_box('上传成功！');
					}else{
						show_message_box(r['info']);
					}
				}
            });
        });
        
    });
</script>
{/literal}

<form id="change_avatar_form" action="{$siteurl}/user/avatar/" method="post" enctype="multipart/form-data">
<div class="content_center">
<img src="{$siteurl}/upload/avatar/{$user->medium_avatar}" id="avatar_img" />
</div>
<table class="table center">
	<tr>
		<td>更改头像</td>
		<td><input type="file" name="avatar" /></td>
	</tr>
	<tr>
		<th></th>
		<td><a href="#" class="submit" id="change_avatar_commit">提交</a></td>
	</tr>
</table>
</form>

</div>