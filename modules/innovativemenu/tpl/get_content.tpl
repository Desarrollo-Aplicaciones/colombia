<div>
        <h2 style="margin : 5px; font-size:20px;">
                <img src="{$logo}"/>
                {$display_name}
        </h2>
        <p style="margin : 5px;">{$description}</p>
</div>
<div class="bo_top" style="float:left;">
        <div style='margin:0;'>
                <ul style='list-style:none; display:block; margin:0; padding:0; background-color: #fff6d3'>
                        <li id='user_guide' class='bo_top_tabs'>
                                <a href='javascript:loadTab("user_guide");'>
                                        <img src='{$module_img}guide.png'/>
                                        {l s='User guide' mod='innovativemenu'}
                                </a>
                        </li>
                        <li id='manage_menus' class='bo_top_tabs bo_top_tabs_2'>
                                <a href='javascript:loadTab("manage_menus");'>
                                        <img src='{$module_img}menus.png'/>
                                        {l s='Edit menus' mod='innovativemenu'}
                                </a>
                        </li>
                        <li id='manage_links' class=' bo_top_tabs bo_top_tabs_2'>
                                <a href='javascript:loadTab("manage_links");'>
                                        <img src='{$module_img}links.png'/>
                                        {l s='Edit links and fonts' mod='innovativemenu'}
                                </a>
                        </li>
                </ul>
        </div>
</div>
<div style="background-color: #fff6D3; border:1px solid #cccccc;">
        

        <div class='clear'>&nbsp;</div>
        <div style='margin:3px;' class='bo_bottom'>{if isset($user_guide)}{$user_guide}{/if}</div>
        
</div>