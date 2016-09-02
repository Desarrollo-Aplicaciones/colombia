
<table class="table" cellspacing=0 cellpadding=0>
        <thead>
                <tr>
                        <th>{l s='Id' mod='innovativemenu'}</th>
                        <th>{l s='Position' mod='innovativemenu'}</th>
                        <th>{l s='Type' mod='innovativemenu'}</th>
                        <th>{l s='Title' mod='innovativemenu'}</th>
                        <th>{l s='Font color' mod='innovativemenu'}</th>
                        <th>{l s='Action' mod='innovativemenu'}</th>
                </tr>
        </thead><tbody>
{foreach from=$all_columns item=value}
        <tr id = "innovative_list_tab_{$value->id|intval}">
                <td>{$value->id_column|intval}</td>
                <td>
                        <input type="hidden" value="{$value->position|htmlentities}" class="column_position_{$value->id_column|intval}" name="column_position"/>
                        <table>
                        {if $value->hasPrevious()} 
                                <tr>
                                        <a href="javascript:moveColumnPosition({$tab->id|intval}, {$value->id_column|intval}, 'down')">
                                                <img src="{$admin_img}up.gif"/>
                                        </a>
                                </tr>
                        {/if}
                        {if $value->hasNext()}
                                <tr>
                                        <a href="javascript:moveColumnPosition({$tab->id|intval}, {$value->id_column|intval}, 'up')">
                                                <img src="{$admin_img}down.gif"/>
                                        </a>
                                </tr>
                        {/if}
                        </table>
                </td>
                <td>{if $value->type == 'text'}{l s='text' mod='innovativemenu'}{elseif $value->type == 'list'}{l s='list' mod='innovativemenu'}{else}{$value->type}{/if}</td>
                <td>
                        {if $value->with_title AND !empty($value->title.$default_lang)}
                                {Tools::htmlentitiesUTF8($value->title.$default_lang)}
                        {elseif $value->type == 'categories'}
                                {Tools::htmlentitiesUTF8($value->getTitleName())}
                        {else}<font color=red>{l s='without title' mod='innovativemenu'}</font>{/if}
                </td>
                <td style="background-color:#{$value->getCSSAttribut('font_color')}">
                        {$value->getCSSAttribut('font_color')}
                </td>
                <td>
                        <table>
                                <td>
                                        <a href="javascript:editColumn({$tab->id|intval}, {$value->id_column|intval});">
                                                        <img src="{$admin_img}edit.gif" alt="{l s='Edit' mod='innovativemenu'}"/>
                                        </a>
                                </td>                                                               
                                <td>&nbsp;</td>
                                <td>
                                        <a href="javascript:deleteColumn({$value->id_column|intval}, {$tab->id|intval});">
                                                        <img src="{$admin_img}delete.gif" alt="{l s='Delete' mod='innovativemenu'}"/>
                                        </a>
                                </td>
                        </table>
                </td>
        </tr>
{/foreach}
</tbody></table>
