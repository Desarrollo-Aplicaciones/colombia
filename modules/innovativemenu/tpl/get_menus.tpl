
<table class="table" cellspacing=0 cellpadding=0>
        <thead>
                <tr>
                        <th>{l s='Id' mod='innovativemenu'}</th>
                        <th>{l s='Name' mod='innovativemenu'}</th>
                        <th>{l s='Color' mod='innovativemenu'}</th>
                        <th>{l s='Height' mod='innovativemenu'}</th>
                        <th>{l s='Width' mod='innovativemenu'}</th>
                        <th>{l s='Actions' mod='innovativemenu'}</th>
                </tr>
        </thead>
        <tbody>
                        
        {foreach from=$response item=res}
                <tr>
                        <td>{$res.id_menu|intval}</td>
                        <td>{Tools::htmlentitiesUTF8($res.name)}</td>
                        <td style="background-color:#{Tools::htmlentitiesUTF8($res.background_color)}">{Tools::htmlentitiesUTF8($res.background_color)}</td>
                        <td>{$res.height|intval}px</td>
                        <td>{$res.width|intval}px</td>
                        <td>
                                <table>
                                        <tr>
                                                <td>
                                                        <a href="javascript:toggleActive('menu', {$res.id_menu|intval});" id="active_menu_{$res.id_menu|intval}">
                                                                <img src="{$admin_img}{if $res.active}enabled.gif{else}disabled.gif{/if}" alt="{l s='switch active' mod='innovativemenu'}"/>
                                                        </a>
                                                </td>
                                                <td>
                                                        <a href="javascript:editMenu({$res.id_menu|intval});">
                                                                <img src="{$admin_img}edit.gif" alt="{l s='Edit' mod='innovativemenu'}"/>
                                                        </a>
                                                </td>

                                                <td>
                                                        <a href="javascript:deleteMenu({$res.id_menu|intval});">
                                                                <img src="{$admin_img}delete.gif" alt="{l s='Delete' mod='innovativemenu'}"/>
                                                        </a>
                                                </td>
                                        </tr>
                                </table>
                        </td>
                </tr>
        {/foreach}
                        
        </tbody>
</table>
{if $no_active AND count($response)}
<div class="hint" style="display:block; float: right; max-width: 40%">
        {l s='No "Rich Menu" is enabled. To activate a "rich menu", you must click on the red cross in the table on the left. This "rich menu" will be displayed in the front office.' mod='innovativemenu'}
</div>
{/if}
