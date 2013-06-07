{$pag_catlog}
<ol>
    {foreach from=$aa_list item=aa}
    <li>
        <a href="{$siteurl}/activity_announcement/{$aa->id}/">{$aa->name}</a>
        <p>
            {$aa->desc}
        </p>
        <span>创建时间：{$aa->create_time}</span>
    </li>
    {/foreach}
</ol>
{$pag_catlog}