
<fieldset>
        <legend>{l s='All advertisement of this tab' mod='innovativemenu'}</legend>
        <div class="clear">&nbsp;</div>
        <table class="table" cellspacing=0 cellpadding=0>
                <thead>
                        <tr>
                                <th>{l s='Id' mod='innovativemenu'}</th>
                                <th>{l s='Position' mod='innovativemenu'}</th>
                                <th>{l s='Title' mod='innovativemenu'}</th>
                                <th>{l s='Action' mod='innovativemenu'}</th>
                        </tr>
                </thead>
                <tbody>
                {foreach from=$all_ads item=value}

                        <tr>
                                <td>{$value->id_ads}</td>
                                <td>
                                        <input type="hidden" value="{$value->position|htmlentities}" class="ads_position_{$value->id_ads|intval}" name="tab_position"/>
                                        <table>
                                        {if $value->hasPrevious()}  
                                                <tr><a href="javascript:moveAdsPosition({$tab->id|htmlentities}, {$value->id_ads|intval}, 'down')">
                                                        <img src="{$admin_img}up.gif"/>
                                                </a></tr>
                                        {/if}
                                        {if $value->hasNext()} 
                                                <tr><a href="javascript:moveAdsPosition({$tab->id|htmlentities}, {$value->id_ads|intval}, 'up')">
                                                        <img src="{$admin_img}down.gif" />
                                                </a></tr>
                                        {/if}
                                        </table>
                                </td>
                                <td>{Tools::htmlentitiesUTF8($value->title.$default_lang)}</td>
                                <td>
                                        <table>
                                                <tr>
                                                        <td>
                                                                <a href="javascript:toggleActive('ads', {$value->id_ads|intval});" id="active_ads_{$value->id_ads|intval}">
                                                                        <img src="{$admin_img}{if $value->active}enabled.gif{else}disabled.gif{/if}" alt="{l s='switch active' mod='innovativemenu'}"/>
                                                                </a>
                                                        </td>
                                                        <td>
                                                                <a href="javascript:editAds({$tab->id|intval}, {$value->id_ads|intval});">
                                                                        <img src="{$admin_img}edit.gif" alt="{l s='Edit' mod='innovativemenu'}"/>
                                                                </a>
                                                        </td>

                                                        <td>
                                                                <a href="javascript:deleteAds({$value->id_ads|intval}, {$tab->id|intval});">
                                                                        <img src="{$admin_img}delete.gif" alt="{l s='Edit' mod='innovativemenu'}"/>
                                                                </a>
                                                        </td>
                                                </tr>
                                        </table>
                                </td>
                        </tr>
                {/foreach}
                </tbody>
        </table>
</fieldset>
<div class="clear">&nbsp;</div>
