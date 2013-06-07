{*
 * Created by JetBrains PhpStorm.
 * Author: Lubo
 * Date: 19/12/11
 * Time: 14:44 
 *}
<link href="{$siteurl}/templates/css/comment.css" rel="stylesheet" type="text/css"/>
{literal}
<script type="text/javascript">
    $(function () {
        //点击‘回复’后，出现回复框，并且‘回复’按钮隐去，随之出现‘取消’按钮
        $('.comment_reply').click(function () {
            $(this).parent().parent().parent().siblings('.reply_text').slideDown();
            $(this).parent().siblings('li').children('.cancel_reply').show();
            $(this).hide();
            return false;
        });
        //点击‘取消’后，回复框消失，并且‘取消’按钮隐去，随之出现‘回复’按钮
        $('.cancel_reply').click(function () {
            $(this).parent().parent().parent().siblings('.reply_text').slideUp();
            $(this).hide();
            $(this).parent().siblings('li').children('.comment_reply').show();
            return false;
        });
        //提交评论的评论
        //另一方面，‘取消’按钮消失，‘回复’按钮出现
        $('.comment_commit').live('click', function () {
            var sub_comment_node = $(this).parent().parent().siblings(".comment_tree_sibling_nodes");
            var reply_text_div = $(this).parent();
            $.post(site_url + "/comment/create", {
                commented_id:$(this).parent().parent().attr('id'),
                content:$(this).siblings('.comment_content').val()
            }, function (data) {
                sub_comment_node.prepend(data);
                reply_text_div.children(".comment_content").val("");
                reply_text_div.slideUp();
            });
            //调整上面两个回复和取消的按钮
            reply_text_div.siblings('.operations').children().children('li').children('.cancel_reply').hide();
            reply_text_div.siblings('.operations').children().children('li').children('.comment_reply').show();
            return false;
        });
        //显示同一级别中的更多评论
        $('.comment_show_more').live('click', function () {
            last_comment_id = $(this).parent().siblings('.last_comment_id').html();
            var clicked_node = $(this);
            $.get(site_url + "/comment/get_siblings/" + last_comment_id, function (data) {
                sibling_comments_html = $(data).filter('.comment_tree_sibling_nodes').html();
                clicked_node.parent().siblings('.last_comment_id').remove();
                clicked_node.parent().parent().append(sibling_comments_html);
                clicked_node.parent().remove();
            });
            return false;
        });
        //显示一条评论的子评论
        $('.show_sub_comment').live('click', function () {
            var clicked_node = $(this);
            $.get(site_url + '/comment/get_children/' + clicked_node.siblings('.comment_item').attr('id'), function (data) {
                clicked_node.siblings('.comment_tree_sibling_nodes').append($(data).html());
                clicked_node.remove();
            });
            return false;
        });
        //删除评论
        $('.comment_delete').live('click', function () {
            var clicked_node = $(this);
            $.get(site_url + '/comment/delete/' + clicked_node.parent().parent().parent().parent().attr('id'), function (data) {
                var res_data = get_ajax_result(data);
                if (res_data.code == 1) {
                    clicked_node.parent().parent().parent().siblings('.comment_content').html(res_data.info);
                } else if (res_data.code == -1) {
                    show_message_box(res_data.info);
                }
            });
            return false;
        });
    });
</script>
{/literal}
{include file='comment/comment_tree_sibling_nodes.tpl' comment_list=$comment_tree}
