{$pag_catlog}
<ol>
    {foreach from=$activities item=activity}
    <li>
        <a href="{$siteurl}/activity/{$activity->id}/">{$activity->name}</a>
        <p>
            {$activity->desc}
        </p>
        <span>创建时间：{$activity->create_time}</span>
    </li>
    {/foreach}
</ol>
{$pag_catlog}