{literal}
<script type="text/javascript">
    $(function(){
        $(".comment_item:last").children(".comment_reply").show();
        $(".comment_commit").live("click",function(){
            commented_id_value=$(this).parents(".comment_item").attr("id");
            content_value=$(this).siblings(".comment_content").val();
            if($.trim(content_value).length<=5){
                show_message_box("发的评论太短啦!");
                return false;
            }
            $.post(site_url+'/comment/create_alias',{
                commented_id:commented_id_value,
                content:content_value
            },function(data){
                $(".comment_list").append(data);
            });
            $(this).parent().slideUp();
            return false;
        });
    });
</script>
{/literal}

<div class="comment_list">
{foreach from=$comment_list item=comment_single}
{include "comment/comment_item.tpl" comment=$comment_single}
{/foreach}
</div>