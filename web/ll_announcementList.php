<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>思目首页</title>
	<link href="css/ll_main.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/jquery-1.5.2.min.js"></script>
</head>

<body>
<table id="tb_center" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td id="p_left_top" valign="top">
		<div id="nav">
			<ul>
				<li><a>首页</a></li>
				<li><a>个人主页</a></li>
				<li><a>主题讨论区</a></li>
				<li><a>校园活动总汇</a></li>
				<li><a>思目基金</a></li>
				<li><a>公示</a></li>
				<li><a>问题与投诉</a></li>
			</ul>
		</div>
	</td>
	<td  id="p_right" rowspan="2" valign="top">
		<div id="content">
			<link href="css/ll_cloud.css" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="js/cloud.js"></script>
			
			<div class="widget , max_width">
				<h2 class="water4">常驻活动</h2>
				<div class="widget_content">
					<div id="cloud">
					<script type="text/javascript">
					$(function(){
				      	   function random_color() {
	                   			//16进制方式表示颜色0-F
	                   			var arrHex = ["0","1","2","3","4","5","6","7","8","9","A","B","C","D"];
	                   			var strHex = "#";
	                   			var index;
	                   			for(var i = 0; i < 6; i++) {
	                   				//取得0-15之间的随机整数
	                   				index = Math.round(Math.random() * 13);
	                   				strHex += arrHex[index];
	                   			}
	                   			return strHex;
	                   		}
	                   		
                       $("#cloud>a").each(function(){
                           var color=random_color();
                          $(this).css('color',color);
                           });
						});
					</script>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
						<a href="#">哈哈哈</a>
					</div>
				</div>
			</div>
			
			<div class="widget , max_width">
				<h2 class="water5">最近活动</h2>
				<div class="widget_content">
					<ol>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
						<li><a href="ll_announcement.php">hahaha</a><span class="right">2011-11-11</span></li>
					</ol>
				</div>
			</div>
		</div>
	</td>
</tr>
<tr>
	<td id="p_left_bottom" valign="top">
		
	</td>
</tr>
<tr>
	<td colspan="2" id="p_bottom">
	</td>
</tr>
</table>
</body>
</html>