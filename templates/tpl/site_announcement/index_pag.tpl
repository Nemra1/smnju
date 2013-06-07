<ol>
    {foreach from=$sas item=sa}
    <li>
        <a href="{$siteurl}/site_announcement/{$sa->id}/">{$sa->name}</a>
		<p>{$sa->desc}</p>
        <span class="right">{$sa->create_time}</span>
    </li>
    {/foreach}
</ol>
{$pag_catlog}
