/**
 * 这段js代码的目的是通过ajax，在页面载入完成后自动插入评论的信息
 * 条件：
 * 1.引入这段代码的html里，有id如topic_id/discussion_id/article_id这样的html tag，就是comment_type+‘_id’的
 * 形式，并且这个tag的text是对应id的数字，通过这个id，可以确定要显示哪些评论
 * 2.在需要显示comment的地方，标记id为如topic_comments/discussion_comments/article_comments的html tag
 * 就是comment_type+'_comments'的形式，通过这个tag，可以知道comments的html应该填在哪里
 * 3.在需要插入对相应subject提交comment的地方填写comment_type+'_comment_add'的html tag，这样就可以知道应该
 * 把提交的框放在哪里
 */

informationNotComplete="information not complete";
permissionDenied="permission denied";
timeTolerant=5000 //多久后报告网络问题

function register_comment_type(comment_type_value){
	$(function() {
		//这个tag里包含着subject的id
		var subject_id_div='#'+comment_type_value+'_id';
        //这个tag标明comment的显示位置
		var comments_div='#'+comment_type_value+'_comments';
        //这个tag标明对subject添加comment的输入框位置
        var comment_add='#'+comment_type_value+'_comment_add';
        //表示提交评论的按钮，关系具体实现，不需要了解
        var comment_submit_button='#'+comment_type_value+'_comment_submit_button';
        //表示评论的内容的tag，关系到具体实现，不需要了解
        var comment_add_content='#'+comment_type_value+'_comment_add_content';

		getAllTimerId=setTimeout("show_form_message_box('网络没有相应，评论获取失败')",timeTolerant);
        //自动加载评论并且添加到comments的div中
		$.get(site_url+"/comment/comment_tree/"+$(subject_id_div).text()+"?comment_type="+comment_type_value,function(data){
			clearTimeout(getAllTimerId);
			$(comments_div).append(data);
		});
        //如果需要显示comment的提交框
        if($(comment_add).length>0){
            ///显示提交框
            $(comment_add).html("<textarea id='"+comment_type_value+"_comment_add_content' rows='5' cols='20' ></textarea><button id='"+comment_type_value+"_comment_submit_button'>提交评论</button>");
            //提交对主题的评论
            $(comment_add+' '+comment_submit_button).click(function() {
                var clicked_node=$(this);
                var comment_content_value=$(comment_add+' '+comment_add_content).val();
                var submitTimerId=setTimeout("show_form_message_box('网络没有相应，评论提交失败')",timeTolerant);
                $.post(site_url+'/comment/create', {
                    comment_type : comment_type_value,
                    commented_id : 0,
                    content : comment_content_value,
                    subject_id :$.trim($( subject_id_div).text()),
                    floor_count:1
                }, function(data) {
                    clicked_node.siblings('textarea').html('');
                    clearTimeout(submitTimerId);
                    if(data==informationNotComplete){
                        show_form_message_box("评论信息不全");
                    }else if(data==permissionDenied){
                        show_form_message_box("没有权限");
                    }
                    $(comments_div).prepend(data);
                    command="CKEDITOR.instances."+comment_type_value+"_comment_add_content.setData('')";
                    eval(command);
                });
                return false;
            });
        }
		//提交回复,提交到服务器的action

	});
}
