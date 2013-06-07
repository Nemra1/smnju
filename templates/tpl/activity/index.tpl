<link href="{$siteurl}/templates/css/cloud.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$siteurl}/templates/js/cloud.js">
</script>
<style>
    #activity_announcement_list img {
        width: 150px !important;
        height: auto !important;
    }
    
   
    /*#cloud {
        float: left;
        margin-left: 100px;
    }*/
</style>

<div class="widget max_width">
    <h2 class="water4 title">常驻活动
	<a class="more" href="{$siteurl}/activity/all">更多</a>
	</h2>
    <div class="widget_content fix_height">
        <div id="cloud">
            {foreach from=$activities item=activity}<a num="{$activity->id}" class="activity_link" href="{$siteurl}/activity/{$activity->id}">{$activity->name}</a>
            {/foreach}
        </div>
    </div>
</div>
<div class="widget max_width">
    <h2 class="water5 title">最近活动
    	<a class="more" href="{$siteurl}/activity_announcement/">更多</a>
    </h2>
	
	
    <div id="activity_announcement_list" class="widget_content simple_list">
        {$aa_list_content}
    </div>
</div>
{literal}
<script type="text/javascript">
    $(function(){
        function random_color(){
            //16进制方式表示颜色0-F
            var arrHex = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B"];
            var strHex = "#";
            var index;
            for (var i = 0; i < 6; i++) {
                //取得0-15之间的随机整数
                index = Math.floor(Math.random() * 13);
                strHex += arrHex[index];
            }
            return strHex;
        }
        
        $("#cloud>a").each(function(){
            var color = random_color();
            $(this).css('color', color);
        });
    });
    
</script>
{/literal}