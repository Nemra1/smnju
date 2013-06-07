{include file="article/admin_header.tpl"}
<div class="widget , max_width">
    <div class="widget_content">
        <table class="stock , center">
            <tr>
                <th>
                    名称
                </th>
                <th>
                    所属主题
                </th>
                <th>
                    创建时间
                </th>
                <th>
                    操作
                </th>
            </tr>
            {foreach from=$articles item=article}
            <tr>
                <td>
                    <a href="{$siteurl}/article/show/{$article->id}">{$article->title} </a>
                </td>
                <td>
                	<a href="{$siteurl}/discussion/topic_show/{$article->topic_id}">{$article->topic_name}</a>
                </td>
				<td>
					<span>{$article->create_time}</span>
				</td>
                <td>
                    <a href="{$siteurl}/article/score/{$article->id}" target="_blank">录入分数</a>
                    <a href="#" num="{$article->id}" class="delete_article">删除</a>
                </td>
            </tr>
            {/foreach}
        </table>
    </div>
</div>
{literal}
<script type="text/javascript">
    $(function(){
        $('.delete_article').click(function(){
            var id = $(this).attr('num');
            show_confirm_message_box('您确定要删除吗？', function(){
                $.post(site_url + '/article/delete/', {
                    activity_id: id
                }, function(data){
                    var r = get_ajax_result(data);
                    if (r['code'] == 0) {
                        location.reload();
                    }
                    else {
                        show_message_box(r['info']);
                    }
                });
                show_wait_message_box('删除中……');
            });
        });
    });
</script>
{/literal}