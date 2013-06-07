{*
 * Created by JetBrains PhpStorm.
 * Author: Lubo
 * Date: 19/12/11
 * Time: 20:01
 需要的变量
 comment_list 是一个comment的数组
 more_comment_count 是相对于comment_list中最后一条评论来讲，还有几条比它早的评论
 last_comment_id comment_list最后一条评论的id
 *}



<div class="comment_tree_sibling_nodes">
{foreach from=$comment_list item=comment}
{include file="comment/comment_tree_node.tpl" comment=$comment}
{/foreach}

{$list_count=$comment_list|@count}
{if $list_count!=0}
    {$last_comment=$comment_list|@end}
    {$more_comment_count=$last_comment->id|@smarty_more_comment_count}
    {if $more_comment_count>10}
        {assign var="show_comment_count" value="10"}
    {else}
        {assign var="show_comment_count" value="$more_comment_count"}
    {/if}
    <div class='last_comment_id' style="display: none">{$last_comment->id}</div>
    {if $more_comment_count!=0}
    <div class='show_more'><a href="#" class='comment_show_more'>点击显示更多{$more_comment_count}中的{$show_comment_count}</a></div>
    {/if}
{/if}
</div>