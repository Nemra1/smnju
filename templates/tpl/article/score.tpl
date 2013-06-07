{include file="article/admin_header.tpl"}
{literal}
<script type="text/javascript">
    $(function(){
        $("#article_score_commit").click(function(){
			
            $.post(url, function(data){
                var r = get_ajax_result(data);
                if (r['code'] == 0) {
                    show_message_box('修改成功');
                }
                else {
                    show_message_box(r['info']);
                }
            });
            show_wait_message_box('修改中……');
            return false;
        });
    })
</script>
{/literal}
<form id="article_score_form,main_form" action="{$siteurl}/article/score/{$article->id}" method="post">
    <div>

       <h3>{$article->title}</h3>
    </div>
	<div>
		{$article->content}
	</div>
    <div>
        <label>
            所属主题
        </label>
		<a href="{$siteurl}/discussion/topic_show/{$article->topic_id}">{$topic->name}</a>
    </div>
	<div></div>
    <div>
        <a href="#" id="article_score_commit">修改</a>
    </div>
</form>
