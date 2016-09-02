
<div class="hint" style="display:block">
        {l s='You can modify the style of tabs if they are in advanced configuration' mod='innovativemenu'}
</div>
<div class="clear">&nbsp;</div>
<table class="table" cellspacing=0 cellpadding=0>
        <thead>
                <tr>
                        <th>{l s='Id' mod='innovativemenu'}</b></th>
                        <th>{l s='Position' mod='innovativemenu'}</b></th>
                        <th>{l s='Type' mod='innovativemenu'}</th>
                        <th>{l s='Name' mod='innovativemenu'}</th>
                        <th>{l s='Font color' mod='innovativemenu'}</th>
                        <th>{l s='Background color' mod='innovativemenu'}</th>
                        <th>{l s='Block background color' mod='innovativemenu'}</th>
                        <th>{l s='Action' mod='innovativemenu'}</th>
                </t>
        </thead>
        <tbody>
        {foreach from=$all_tabs item=value}
                <tr id = "innovative_list_tab_{$value->id_tab|intval}">
                        <td>
                                {$value->id_tab|intval}
                        </td>
                        <td>
                                <input type="hidden" value="{$value->position|intval}" class="tab_position_{$value->id_tab|intval}" name="tab_position"/>
                                <table>
                                {if $value->hasPrevious()} 
                                        <tr><a href="javascript:moveTabPosition({$menu->id|intval}, {$value->id_tab|intval}, 'down')">
                                                <img src="{$admin_img}up.gif" />
                                        </a></tr>
                                {/if}
                                {if $value->hasNext()} 
                                        <tr><a href="javascript:moveTabPosition({$menu->id|intval}, {$value->id_tab|intval}, 'up')">
                                                <img src="{$admin_img}down.gif" />
                                        </a></tr>
                                {/if}
                                </table>
                        </td>
                        <td>
                                {$value->getTypeTraduction()}
                        </td>
                        <td>
                                {$value->getNameOfLink()}
                        </td>
                        <td style="background-color:#{$value->getCSSAttribut('font_color')}">
                                {$value->getCSSAttribut('font_color')}
                        </td>
                        <td style="background-color:#{$value->getCSSAttribut('background_color')}">
                                {$value->getCSSAttribut('background_color')}
                        </td>
                        <td style="background-color:#{$value->getCSSAttribut('block_background_color')}">
                                {$value->getCSSAttribut('block_background_color')}
                        </td>
                        <td>
                                <table>
                                        <td>
                                                <a href="javascript:editTab({$menu->id|intval}, {$value->id_tab|intval});">
                                                                <img src="{$admin_img}edit.gif" alt="{l s='Edit' mod='innovativemenu'}"/>
                                                </a>
                                        </td>                                                               
                                        <td>
                                                <a href="javascript:deleteTab({$value->id_tab|intval},  {$menu->id|intval});">
                                                                <img src="{$admin_img}delete.gif" alt="{l s='Delete' mod='innovativemenu'}"/>
                                                </a>
                                        </td>
                                </table>
                        </td>
                </tr>
        {/foreach}
        </tbody>
</table>
