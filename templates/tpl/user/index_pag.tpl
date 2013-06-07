<style>
    .avatar_area img {
        max-width: 150px;
    }
    
    .avatar_area {
        float: left;
        margin-right: 40px;
    }
    
    .info_area {
        float: left;
    }
    
    li.user_area {
        margin-bottom: 10px;
    }
    
    li.user_area h4 a {
        font-size: 21px;
        font-weight: bold;
    }
</style>
{$pag_catlog}
<ul>
    {foreach from=$users item=user}
    <li class="fix_height user_area">
        <div class="avatar_area">
            <a href="{$siteurl}/user/show/{$user->id}"><img src="{$siteurl}/upload/avatar/{$user->medium_avatar}" /></a>
        </div>
        <div class="info_area">
            <h4><a href="{$siteurl}/user/show/{$user->id}">{$user->alias_name}</a></h4>
            <p>
                {if $user->name}
                {$user->name}
                {/if}
            </p>
            <p>
                {if $user->institute} 
                {$user->institute}  
                {/if} 
            </p>
            <p>
                {if $user->gender}
                {$user->gender}
                {/if}
            </p>
            <p>
                {if $user->telnum}
                {$user->telnum}
                {/if}
            </p>
            <p>
                {if $user->email}
                {$user->email}
                {/if}
            </p>
        </div>
    </li>
    {/foreach}
</ul>
{$pag_catlog}