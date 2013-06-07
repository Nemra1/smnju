{include file="user/header.tpl"}
<div class="">
{if $person_same and not $user->is_applicant}
<div class="keylink"><a href="{$siteurl}/award/apply/">>> 申请奖学金 <<</a></div>
{/if}


<div id="sys_user">
	<div class="mwidget small_width blockright">
		<div class="content">
			<img width="200" src="{$siteurl}/upload/avatar/{$user->medium_avatar}" id="user_head" />
			
			<table class="center" style="width:180px;margin-top: 10px;">
				{if $user_profile->name}
				<tr>
					<th>姓名&nbsp;</th>
					<td>{$user_profile->name}</td>
				</tr>
				{/if}
				
				<tr>
					<th>昵称</th>
					<td>{$user_profile->alias_name}</td>
				</tr>
				
				{if $user_profile->institute}
				<tr>
					<th>院系</th>
					<td>{$user_profile->institute}</td>
				</tr>
				{/if} 
				
				
				{if $user_profile->gender}
				<tr>
					<th>性别</th>
					<td>{$user_profile->gender}</td>
				</tr>
				{/if}
				
				{if $user_profile->telnum}
				<tr>
					<th>手机</th>
					<td>{$user_profile->telnum}</td>
				</tr>
				{/if}
				
				{if $user_profile->email}
				<tr>
					<th>邮箱</th>
					<td>{$user_profile->email}</td>
				</tr>
				{/if}
			
			</table>
		</div>
	</div>
	
	<div class="widget large_width" >
       <div id="news_area">
       	{$news_list_content}
       </div>
	</div>
</div>
</div>