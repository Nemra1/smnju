<link href="/templates/css/common.css" rel="stylesheet" type="text/css" />
<form id="user_login_form,main_form" action="{$siteurl}/admin/login_simuren/" method="post">
<div class="common">
	<div class="roundSide , center , innerCenter" 
			style="width: 300px;margin-top: 100px;">
		<h2>用户登录</h2>
		<div style="padding-top: 15px;">
			
			<label>学号</label>
			<input type="text" name="student_number"/>
		</div>
		<div style="padding-top: 15px;">
			<label>密码</label>
			<input type="password" name="password"/>
		</div>
		<div style="padding-top: 15px;">
			<input type="submit" value="登录" />
		</div>
	</div>
</div>
</form>