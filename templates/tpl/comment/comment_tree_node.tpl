{*
 * Author: Lubo
 * Date: 19/12/11
 * Time: 14:51
 需要的变量
 comment
 comment里有个所属的user变量
 comment里有个所属的sub_comment_list变量
 *}
<div class="comment_tree_node">
{include file="comment/comment_item.tpl" comment=$comment}
    {$sub_comment_list_count=$comment->sub_comment_list|@count}
    {if $comment->sub_comment_count!=0 and $sub_comment_list_count==0}
    <a class="show_sub_comment" href="#">显示子评论</a>
    {/if}
{include file="comment/comment_tree_sibling_nodes.tpl" comment_list=$comment->sub_comment_list}
</div>