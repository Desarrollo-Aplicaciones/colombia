
{if isset($edit_menu)}
<div class="clear">&nbsp;</div>
<div style="margin:0; padding:0;">
        <!--legend>{l s='Configuration' mod='innovativemenu'}&nbsp;{if $menu->id}{Tools::htmlentitiesUTF8($menu->name)}{else}{l s='new menu' mod='innovativemenu'}{/if}</legend-->
        <ul class='bo_tabs'>
                <li class='submenu_size2 active' id="menu_general_config"><a href="javascript:toggleBlock2('menu_general_config')">{l s='General configuration of menu bar' mod='innovativemenu'}</a></li>
                {if $menu->id}<li class='submenu_size' id="menu_tabs_config"><a href="javascript:toggleBlock2('menu_tabs_config')">{l s='Tabs configuration' mod='innovativemenu'}</a></li>{/if}
        </ul>
        <div class="tab_module_content" id="menu_general_config_content">
                <fieldset><form class="menu_data">
                        <input type="hidden" name="id_menu" value="{$menu->id}"/>
                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='General configuration of menu bar' mod='innovativemenu'}</div>
                        <div class="clear">&nbsp;</div>
                        
                        <div class='innovative_div'>
                                <label>{l s='Name' mod='innovativemenu'}</label>
                                <input type="text" name="menu_name" value="{if $menu->id}{Tools::htmlentitiesUTF8($menu->name)}{/if}"/>
                                <div class='margin-form'>{l s='Name or title of this menu' mod='innovativemenu'}.&nbsp;{l s='This title will not appear on your website.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Height' mod='innovativemenu'}</label>
                                <input type="text" name="menu_height" size=6 value="{if $menu->id}{$menu->height|intval}{else}{Innovative_Menu::$d_height|intval}{/if}"/> px
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        <div class='innovative_div'>
                                <label>{l s='Width' mod='innovativemenu'}</label>
                                <input type="text" name="menu_width" size=6 value="{if $menu->id}{$menu->width|intval}{else}{Innovative_Menu::$d_width|intval}{/if}"/> px
                                <div class='margin-form'>{l s='In back office, the width of the menu can extend beyond. But, this is not a bug' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Background color' mod='innovativemenu'}</label>
                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_background_color"
                                        value="{if $menu->id}{Tools::htmlentitiesUTF8($menu->background_color)}{else}{Innovative_menu::$d_background_color|htmlentities}{/if}"
                                        style="background-color:#{if $menu->id}{$menu->background_color|htmlentities}{else}{Innovative_menu::$d_background_color|htmlentities}{/if}"/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        <div class='innovative_div'>
                                <label>{l s='Font color' mod='innovativemenu'}</label>
                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_font_color"
                                        value="{if $menu->id}{$menu->font_color|htmlentities}{else}{Innovative_menu::$d_font_color|htmlentities}{/if}"
                                        style="background-color:#{if $menu->id}{$menu->font_color|htmlentities}{else}{Innovative_menu::$d_font_color|htmlentities}{/if}"/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font style' mod='innovativemenu'}</label>
                                <select name="menu_font_style">
                                        {$text_menu_font_style}
                                <select/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        
                        <div class='innovative_div'>
                                <label>{l s='Font weight' mod='innovativemenu'}</label>
                                <select name="menu_font_weight">
                                        {$text_menu_font_weight}
                                <select/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font family' mod='innovativemenu'}</label>
                                <select name="menu_font_family">
                                        {$text_menu_font_family}
                                <select/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font size' mod='innovativemenu'}</label>
                                <select name="menu_font_size">
                                        {$text_menu_font_size}
                                <select/> px
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Border top radius' mod='innovativemenu'}</label>
                                <input name="menu_border_top_radius"  type='text' size='3'
                                       value='{if $menu->id}{$menu->border_top_radius|intval}{else}{Innovative_Menu::$d_border_top_radius|intval}{/if}'/>
                                <div class='margin-form'>{l s='Choose the rounding of the top borders. 0 if no top border is applied.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Border bottom radius' mod='innovativemenu'}</label>
                                <input name="menu_border_bottom_radius"  type='text' size='3'
                                       value='{if $menu->id}{$menu->border_bottom_radius|intval}{else}{Innovative_Menu::$d_border_bottom_radius|intval}{/if}'/>
                                <div class='margin-form'>{l s='Choose the rounding of the bottom borders. 0 if no bottom border is applied.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        
                        <div class='innovative_div'>
                                <label>{l s='Active general configuration ?' mod='innovativemenu'}</label>
                                <input type='checkbox' value='1' name='menu_general_configuration' onchange="javascript:toggleMenuGeneralConfiguration(this)"
                                {if ($menu->id && $menu->general_configuration) || !$menu->id}checked{/if}/>
                                <div class='margin-form'>{l s='If you choose this option, all the tabs will have the same appearance.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        
                        <div id='general_configuration' style='display:{if ($menu->id && $menu->general_configuration) || !$menu->id}block{else}none{/if}'>
                                <div class="clear">&nbsp;</div>
                                <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='General configuration of tabs when mouse is over' mod='innovativemenu'}</div>
                                <div class="clear">&nbsp;</div>
                                <div class='innovative_div'>
                                        <label>{l s='Background color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_tab_background_color"
                                                value="{if $menu->id}{$menu->tab_background_color|htmlentities}{else}{Innovative_menu::$d_tab_background_color|htmlentities}{/if}"
                                                style="background-color:#{if $menu->id}{$menu->tab_background_color|htmlentities}{else}{Innovative_menu::$d_tab_background_color|htmlentities}{/if}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
				
		                <div class='innovative_div'>
		                        <label>{l s='Font color hover' mod='innovativemenu'}</label>
		                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_font_color_hover"
		                                value="{if $menu->id}{$menu->font_color_hover|htmlentities}{else}{Innovative_menu::$d_font_color_hover|htmlentities}{/if}"
		                                style="background-color:#{if $menu->id}{$menu->font_color_hover|htmlentities}{else}{Innovative_menu::$d_font_color_hover|htmlentities}{/if}"/>
		                        <div class='margin-form'>&nbsp;</div>
		                </div>
		                <div class="clear">&nbsp;</div>

                                <div class="clear">&nbsp;</div>
                                <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='General configuration of the blocks of every tab' mod='innovativemenu'}</div>
                                <div class="clear">&nbsp;</div>
                                <div class='innovative_div'> 
                                        <label>{l s='Font size' mod='innovativemenu'}</label>
                                        <select name="menu_block_font_size">
                                                {$text_block_font_size}
                                        </select>px
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'> 
                                        <label>{l s='Font style' mod='innovativemenu'}</label>
                                        <select name="menu_block_font_style">
                                                {$text_block_font_style}
                                        </select>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'> 
                                        <label>{l s='Font weight' mod='innovativemenu'}</label>
                                        <select name="menu_block_font_weight">
                                                {$text_block_font_weight}
                                        </select>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'> 
                                        <label>{l s='Font family' mod='innovativemenu'}</label>
                                        <select name="menu_block_font_family">
                                                {$text_block_font_family}
                                        </select>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>


                                <div class='innovative_div'>
                                        <label>{l s='Font color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_block_font_color"
                                                value="{if $menu->id}{$menu->block_font_color|htmlentities}{else}{Innovative_menu::$d_block_font_color|htmlentities}{/if}"
                                                style="background-color:#{if $menu->id}{$menu->block_font_color|htmlentities}{else}{Innovative_menu::$d_block_font_color|htmlentities}{/if}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>


                                <div class='innovative_div'>
                                        <label>{l s='Background color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_block_background_color"
                                                value="{if $menu->id}{$menu->block_background_color|htmlentities}{else}{Innovative_menu::$d_block_background_color|htmlentities}{/if}"
                                                style="background-color:#{if $menu->id}{$menu->block_background_color|htmlentities}{else}{Innovative_menu::$d_block_background_color|htmlentities}{/if}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>


                                <div class='innovative_div'>
                                        <label>{l s='Border color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_block_border_color"
                                                value="{if $menu->id}{$menu->block_border_color|htmlentities}{else}{Innovative_menu::$d_block_border_color|htmlentities}{/if}"
                                                style="background-color:#{if $menu->id}{$menu->block_border_color|htmlentities}{else}{Innovative_menu::$d_block_border_color|htmlentities}{/if}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>


                                <div class='innovative_div'>
                                        <label>{l s='Border width' mod='innovativemenu'}</label>
                                        <input size="2" maxlength="2" type="text" name="menu_block_border_width"
                                                value="{if $menu->id}{$menu->block_border_width|intval}{else}{Innovative_menu::$d_block_border_width|intval}{/if}"/> px
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Column width' mod='innovativemenu'}</label>
                                        <input size="2" type="text" name="menu_column_width"
                                                value="{if $menu->column_width}{$menu->column_width|htmlentities}{else}{Innovative_Menu::$d_column_width|htmlentities}{/if}"/> px
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                
                                <div class='innovative_div'>
                                        <label>{l s='With horizontal line ?' mod='innovativemenu'}</label>
                                        <input type='checkbox' name="menu_column_title_with_horizontal_line" value='1' {if $menu->id && $menu->column_title_with_horizontal_line}checked{/if}
                                                      onClick='javascript:toggleDiv($(this), $(".config_vertical_line"))'/>
                                        <div class='margin-form'>{l s='Title and content of columns separate with horizontal line ?' mod='innovativemenu'}</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                <div class='config_vertical_line' style='display:{if $menu->id && $menu->column_title_with_horizontal_line}block{else}none{/if}'>
                                        <div class='innovative_div'>
                                                <label>{l s='Line width' mod='innovativemenu'}</label>
                                                <input type='text' name='menu_column_title_horizontal_line_width' size="6" maxlength="6"
                                                       value='{if $menu->id}{$menu->column_title_horizontal_line_width}{else}1{/if}'>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>

                                                <label>{l s='Line color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_column_title_horizontal_line_color"
                                                        value="{if $menu->id}{$menu->column_title_horizontal_line_color|htmlentities}{else}{Innovative_menu::$d_column_title_font_color|htmlentities}{/if}"
                                                        style="background-color:#{if $menu->id}{$menu->column_title_horizontal_line_color|htmlentities}{else}{Innovative_menu::$d_column_title_font_color|htmlentities}{/if}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='With vertical line ?' mod='innovativemenu'}</label>
                                        <input type='checkbox' name="menu_column_with_border_left" value='1' {if $menu->id && $menu->column_with_border_left}checked{/if}
                                              onClick='javascript:toggleDiv($(this), $(".config_horizontal_line"))'/>
                                        <div class='margin-form'>{l s='Columns separate by vertical line ?' mod='innovativemenu'}</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='config_horizontal_line' style='display:{if $menu->id && $menu->column_with_border_left}block{else}none{/if}'>
                                        <div class='innovative_div'>
                                                <label>{l s='Line width' mod='innovativemenu'}</label>
                                                <input type='text' name='menu_column_border_left_width' size="6" maxlength="6"
                                                       value='{if $menu->id}{$menu->column_border_left_width}{else}1{/if}'>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>

                                                <label>{l s='Line color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_column_border_left_color"
                                                        value="{if $menu->id}{$menu->column_border_left_color|htmlentities}{else}{Innovative_menu::$d_block_font_color|htmlentities}{/if}"
                                                        style="background-color:#{if $menu->id}{$menu->column_border_left_color|htmlentities}{else}{Innovative_menu::$d_block_font_color|htmlentities}{/if}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                </div>

                                <div class="clear">&nbsp;</div>
                                <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='General configuration of title of columns' mod='innovativemenu'}</div>
                                <div class="clear">&nbsp;</div>
                                <div class='innovative_div'>

                                        <label>{l s='Font color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_column_title_font_color"
                                                value="{if $menu->id}{$menu->column_title_font_color|htmlentities}{else}{Innovative_menu::$d_column_title_font_color|htmlentities}{/if}"
                                                style="background-color:#{if $menu->id}{$menu->column_title_font_color|htmlentities}{else}{Innovative_menu::$d_column_title_font_color|htmlentities}{/if}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font style' mod='innovativemenu'}</label>
                                        <select name="menu_column_title_font_style">
                                                {$text_column_title_font_style}
                                        <select/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Font weight' mod='innovativemenu'}</label>
                                        <select name="menu_column_title_font_weight">
                                                {$text_column_title_font_weight}
                                        <select/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font family' mod='innovativemenu'}</label>
                                        <select name="menu_column_title_font_family">
                                                {$text_column_title_font_family}
                                        <select/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font size' mod='innovativemenu'}</label>
                                        <select name="menu_column_title_font_size">
                                                {$text_column_title_font_size}
                                        <select/> em
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Text underline ?' mod='innovativemenu'}</label>
                                        <input type='checkbox' name="menu_column_title_underline" value='1' {if $menu->id && $menu->column_title_underline}checked{/if}/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

				<div class="clear">&nbsp;</div>
                                <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Configuration of lists in columns when mouse is over' mod='innovativemenu'}</div>
				<div class="clear">&nbsp;</div>
				<div class='innovative_div'>
		                        <label>{l s='Font color hover' mod='innovativemenu'}</label>
		                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="menu_column_list_font_color_hover"
		                                value="{if $menu->id}{$menu->column_list_font_color_hover|htmlentities}{else}{Innovative_menu::$d_font_color_hover|htmlentities}{/if}"
		                                style="background-color:#{if $menu->id}{$menu->column_list_font_color_hover|htmlentities}{else}{Innovative_menu::$d_font_color|htmlentities}{/if}"/>
		                        <div class='margin-form'>{l s='Font color of list in columns when mouse is over.' mod='innovativemenu'}</div>
		                </div>
		                <div class="clear">&nbsp;</div>

				<div class='innovative_div'>
                                        <label>{l s='Font style' mod='innovativemenu'}</label>
                                        <select name="menu_column_list_font_style_hover">
                                                {$text_column_list_font_style_hover}
                                        <select/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Font weight' mod='innovativemenu'}</label>
                                        <select name="menu_column_list_font_weight_hover">
                                                {$text_column_list_font_weight_hover}
                                        <select/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Text underline' mod='innovativemenu'}</label>
                                        <input type='checkbox' name='menu_column_list_underline_hover' {if $menu->id && $menu->column_list_underline_hover}checked{/if}/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                        </div>
                        <div class='bo_center'>
                                <button class="button" type="button" onClick="javascript:saveMenu({$menu->id|intval})">
                                        {l s='Save menu' mod='innovativemenu'}
                                </button>
                        </div>
                        <div class="clear">&nbsp;</div>
                </form></fieldset>
        </div>
                
        {if $menu->id}
        <div class="tab_module_content" id="menu_tabs_config_content" style='display:none;'>
                <div class="innovative_all_tabs_{$menu->id}">{$tabs_of_menu}</div>

                <div class="clear">&nbsp;</div>
                <a style="display:block;" href="javascript:editTab({$menu->id}, 0, true)">
                        <img src="{$admin_img}add.gif" alt="{l s='Add tab' mod='innovativemenu'}"/>
                        {l s='Add tab' mod='innovativemenu'}
                </a>

                <div class="clear">&nbsp;</div>
                <div class="innovative_edit_tab"></div>
        </div>
        {/if}        
        
        

        
        
        
        
        
        
        
{* Edit tab form *}       
{elseif isset($edit_tab)}
        <legend>{if $tab->id}{l s='Tab' mod='innovativemenu'}&nbsp;{$tab->id}{else}{l s='New Tab' mod='innovativemenu'}{/if}</legend>
        <div class="header_module" id="tab_{$tab->id}_general_config">
                <a href="javascript:toggleBlock('tab_{$tab->id}_general_config')">
                        <span style="padding-right:0.5em">
                                <img id="tab_{$tab->id}_general_config_img" class="header_module_img" alt="" src="{$admin_img}less.png">
                        </span>
                        <b>{l s='General setting' mod='innovativemenu'}</b>
                </a>
        </div>
        <div class="tab_module_content" style="border: 1px solid #cccccc" id="tab_{$tab->id}_general_config_content">
                <div class="clear">&nbsp;</div>
                <form class="innovative_tab_form" id="innovative_tab_form_{$tab->id|intval}">
                        <input type="hidden" name="id_menu" value="{$tab->id_menu|intval}"/>
                        <input type="hidden" name="id_tab" value="{$tab->id|intval}"/>

                        <div class='innovative_div'>
                                <label>{l s='Type of tab' mod='innovativemenu'}</label>
                                <select class="tab_type" name="tab_type" onChange="javascript:editTabType($(this).val(), {$tab->id_menu|intval}, {$tab->id|intval});">{$part}</select>
                                <div class='margin-form'>{l s='The type of tab allows to propose a coherent contents in every block.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class="configure_tabs_content_{$tab->id|intval} innovative_div">
                                {$configure_tabs_content}
                                <br/>
                                <div class='margin-form'>{l s='Configuration of the contents of blocks according to the type of this tab.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='With advertisement ?' mod='innovativemenu'}</label>
                                <input type='checkbox' {if $tab->with_ads}checked{/if} onchange='toggleWithAds(this, {$tab->id|intval})' value= '1' name='tab_with_ads'/>
                                <div class='margin-form'>{l s='Do you want to add an advertising to this tab?' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='tab_with_ads_{$tab->id|intval}' style='display:{if $tab->with_ads}block{else}none;{/if}'>
                                <div class='innovative_div'>
                                        <label>{l s='Advertising align' mod='innovativemenu'}</label>
                                        <select name="tab_ads_align">
                                        {$select_advertising_align}
                                        </select>
                                        <div class='margin-form'>{l s='The block of advertisements can be aligned at the top, at the bottom, to the left or to the right.' mod='innovativemenu'}</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Advertising width' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" type="text" name="tab_ads_width"
                                                value="{if $tab->id}{$tab->ads_width|intval}{else}32{/if}"/>&nbsp;%
                                        <div class='margin-form'>{l s='Width of the each advertising according to the total width of the block.' mod='innovativemenu'}</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Advertising background color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_ads_background_color"
                                                value="{$tab->ads_background_color}"
                                                style="background-color:#{$tab->ads_background_color}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Advertising font color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_ads_font_color"
                                                value="{$tab->ads_font_color}"
                                                style="background-color:#{$tab->ads_font_color}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                        </div>

                        <div class='tab_general_configuration' style='display:{if $menu->general_configuration}none{else}block{/if}'>
                                <div class="clear">&nbsp;</div>

                                <div class="configure_tabs_appearance">
                                        <div class='innovative_div'>
                                                <label>{l s='background color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_background_color"
                                                        value="{$tab->background_color|htmlentities}"
                                                        style="background-color:#{$tab->background_color|htmlentities}"/>
                                                <div class='margin-form'>{l s='Color of the tab when mouse is over.' mod='innovativemenu'}</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='font color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_font_color"
                                                        value="{$tab->font_color|htmlentities}" style="background-color:#{$tab->font_color|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='font color hover' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_font_color_hover"
                                                        value="{$tab->font_color_hover|htmlentities}" style="background-color:#{$tab->font_color_hover|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                        <div class="clear">&nbsp;</div>

                                        
                                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Block setting' mod='innovativemenu'}</div>
                                        <div class="clear">&nbsp;</div>
                                        <div class='innovative_div'>
                                                <label>{l s='background color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_block_background_color"
                                                        value="{$tab->block_background_color|htmlentities}" style="background-color:#{$tab->block_background_color|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='border color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_block_border_color"
                                                        value="{$tab->block_border_color|htmlentities}" style="background-color:#{$tab->block_border_color|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='border width' mod='innovativemenu'}</label>
                                                <input size="2" type="text" name="tab_block_border_width"
                                                        value="{$tab->block_border_width|intval}"/> px
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                         <div class="clear">&nbsp;</div>
                                        
                                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Columns setting' mod='innovativemenu'}</div>
                                        <div class="clear">&nbsp;</div>
                                        
                                        <div class='innovative_div'>
                                                <label>{l s='Width' mod='innovativemenu'}</label>
                                                <input size="2" type="text" name="tab_column_width"
                                                        value="{$tab->column_width|intval}"/> px
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font size' mod='innovativemenu'}</label>
                                                <select name="tab_column_font_size">
                                                        {$select_column_font_size}
                                                </select> px
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font family' mod='innovativemenu'}</label>
                                                <select name="tab_column_font_family">
                                                        {$select_column_font_family}
                                                </select>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font weight' mod='innovativemenu'}</label>
                                                <select name="tab_column_font_weight">
                                                        {$select_column_font_weight}
                                                </select>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font style' mod='innovativemenu'}</label>
                                                <select name="tab_column_font_style">
                                                        {$select_column_font_style}
                                                </select>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_column_font_color"
                                                        value="{if $tab->column_font_color}{$tab->column_font_color|htmlentities}{else}{$menu->block_font_color}{/if}"
                                                        style="background-color:#{if $tab->column_font_color}{$tab->column_font_color|htmlentities}{else}{$menu->block_font_color|htmlentities}{/if}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                        
                                        <div class='innovative_div'>
                                                <label>{l s='With horizontal line ?' mod='innovativemenu'}</label>
                                                <input type='checkbox' name="tab_column_title_with_horizontal_line" value='1' {if $tab->column_title_with_horizontal_line}checked{/if}
                                                              onClick='javascript:toggleDiv($(this), $(".config_vertical_line"))'/>
                                                <div class='margin-form'>{l s='Title and content of columns separate with horizontal line ?' mod='innovativemenu'}</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                        <div class='config_vertical_line' style='display:{if $tab->column_title_with_horizontal_line}block{else}none{/if}'>
                                                <div class='innovative_div'>
                                                        <label>{l s='Line width' mod='innovativemenu'}</label>
                                                        <input type='text' name='tab_column_title_horizontal_line_width' size="6" maxlength="6"
                                                               value='{$tab->column_title_horizontal_line_width}'>
                                                        <div class='margin-form'>&nbsp;</div>
                                                </div>
                                                <div class="clear">&nbsp;</div>

                                                <div class='innovative_div'>

                                                        <label>{l s='Line color' mod='innovativemenu'}</label>
                                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_column_title_horizontal_line_color"
                                                                value="{$tab->column_title_horizontal_line_color|htmlentities}"
                                                                style="background-color:#{$tab->column_title_horizontal_line_color|htmlentities}"/>
                                                        <div class='margin-form'>&nbsp;</div>
                                                </div>
                                        </div>

                                        <div class="clear">&nbsp;</div>
                                        <div class='innovative_div'>
                                                <label>{l s='With vertical line ?' mod='innovativemenu'}</label>
                                                <input type='checkbox' name="tab_column_with_border_left" value='1' {if $tab->column_with_border_left}checked{/if}
                                                      onClick='javascript:toggleDiv($(this), $(".config_horizontal_line"))'/>
                                                <div class='margin-form'>{l s='Columns separate by vertical line ?' mod='innovativemenu'}</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='config_horizontal_line' style='display:{if $tab->column_with_border_left}block{else}none{/if}'>
                                                <div class='innovative_div'>
                                                        <label>{l s='Line width' mod='innovativemenu'}</label>
                                                        <input type='text' name='tab_column_border_left_width' size="6" maxlength="6"
                                                               value='{$menu->column_border_left_width}'>
                                                        <div class='margin-form'>&nbsp;</div>
                                                </div>
                                                <div class="clear">&nbsp;</div>

                                                <div class='innovative_div'>

                                                        <label>{l s='Line color' mod='innovativemenu'}</label>
                                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_column_border_left_color"
                                                                value="{$tab->column_border_left_color|htmlentities}"
                                                                style="background-color:#{$tab->column_border_left_color|htmlentities}"/>
                                                        <div class='margin-form'>&nbsp;</div>
                                                </div>
                                        </div>

                                        
                                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Titles of columns setting' mod='innovativemenu'}</div>
                                        <div class="clear">&nbsp;</div>
                                        <div class='innovative_div'>
                                                <label>{l s='Font size' mod='innovativemenu'}</label>
                                                <select name="tab_column_title_font_size">
                                                        {$select_column_title_font_size}
                                                </select> em
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font family' mod='innovativemenu'}</label>
                                                <select name="tab_column_title_font_family">
                                                        {$select_column_title_font_family}
                                                </select>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font weight' mod='innovativemenu'}</label>
                                                <select name="tab_column_title_font_weight">
                                                        {$select_column_title_font_weight}
                                                </select>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font style' mod='innovativemenu'}</label>
                                                <select name="tab_column_title_font_style">
                                                        {$select_column_title_font_style}
                                                </select>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_column_title_font_color"
                                                        value="{$tab->column_title_font_color|htmlentities}"
                                                        style="background-color:#{$tab->column_title_font_color|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Text underline ?' mod='innovativemenu'}</label>
                                                <input type='checkbox' name="tab_column_title_underline" value='1' {if $tab->column_title_underline}checked{/if}/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                        
                                        
                                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Configuration of lists in columns when mouse is over' mod='innovativemenu'}</div>
                                        <div class="clear">&nbsp;</div>
                                        <div class='innovative_div'>
                                                <label>{l s='Font color hover' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="tab_column_list_font_color_hover"
                                                        value="{$tab->column_list_font_color_hover|htmlentities}"
                                                        style="background-color:#{$tab->column_list_font_color_hover|htmlentities}"/>
                                                <div class='margin-form'>{l s='Font color of list in columns when mouse is over.' mod='innovativemenu'}</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font style' mod='innovativemenu'}</label>
                                                <select name="tab_column_list_font_style_hover">
                                                        {$select_column_list_font_style_hover}
                                                <select/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>
                                                <label>{l s='Font weight' mod='innovativemenu'}</label>
                                                <select name="tab_column_list_font_weight_hover">
                                                        {$select_column_list_font_weight_hover}
                                                <select/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                        
                                        <div class='innovative_div'>
                                                <label>{l s='Text underline' mod='innovativemenu'}</label>
                                                <input type='checkbox' name='tab_column_list_underline_hover' {if $tab->column_list_underline_hover}checked{/if}/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                </div>
                        </div>
                        <div class='bo_center'>
                                <button class="button" type="button" onClick="javascript:saveTab({intval($menu->id)}, {intval($tab->id)})">
                                        {l s='Save tab' mod='innovativemenu'}
                                </button>
                        </div>
                        <div class="clear">&nbsp;</div>
                </form>
        </div>
        {if $has_type}
        <div class="header_module" id="tab_{$tab->id}_columns_config">
                <a href="javascript:toggleBlock('tab_{$tab->id|htmlentities}_columns_config')">
                        <span style="padding-right:0.5em">
                                <img id="tab_{$tab->id}_columns_config_img" class="header_module_img" alt="" src="{$admin_img}less.png">
                        </span>
                        <b>{l s='Columns setting' mod='innovativemenu'}</b>
                </a>
        </div>
        <div class="tab_module_content" style="border: 1px solid #cccccc" id="tab_{$tab->id|htmlentities}_columns_config_content">
                <div class="clear">&nbsp;</div>
                <div class="innovative_all_columns_{$tab->id|intval}">{$all_columns}</div>

                <div class="clear">&nbsp;</div>
                <a style="display:block;" href="javascript:editColumn({$tab->id|intval})">
                        <img src="{$admin_img}add.gif" alt="{l s='Add column' mod='innovativemenu'}"/>
                        {l s='Add column' mod='innovativemenu'}
                </a>

                <div class="clear">&nbsp;</div>
                <div class="innovative_edit_column"></div>
        </div>
        {/if}
        
        {if $tab->with_ads}
        <div class="header_module" id="tab_{$tab->id}_ads_config">
                <a href="javascript:toggleBlock('tab_{$tab->id|intval}_ads_config')">
                        <span style="padding-right:0.5em">
                                <img id="tab_{$tab->id|htmlentities}_ads_config_img" class="header_module_img" alt="" src="{$admin_img}less.png">
                        </span>
                        <b>{l s='Advertising setting' mod='innovativemenu'}</b>
                </a>
        </div>
        <div class="tab_module_content" style="border: 1px solid #cccccc;" id="tab_{$tab->id|intval}_ads_config_content">
                <div class="clear">&nbsp;</div>
                <div class="innovative_all_ads_{$tab->id|intval}">{$all_ads}</div>

                <div class="clear">&nbsp;</div>
                <a style="display:block;" href="javascript:editAds({$tab->id|intval})">
                        <img src="{$admin_img}add.gif" alt="{l s='Add advertising' mod='innovativemenu'}"/>
                        {l s='Add advertising' mod='innovativemenu'}
                </a>

                <div class="clear">&nbsp;</div>
                <div class="innovative_edit_ads"></div>
        </div>
        {/if}
        
        
        
 
        
        
{elseif isset($edit_column)}
<fieldset>
        <legend>{l s='Column' mod='innovativemenu'}&nbsp;{$column->id|intval}</legend>
        <form class="innovative_column_form" id="innovative_column_form_{$column->id|intval}">
                <input type="hidden" name="id_tab" value="{$column->id_tab|intval}"/>
                <input type="hidden" name="id_column" value="{$column->id|intval}"/>                        

                <div class='innovative_div'>
                        <label>{l s='Select type of column' mod='innovativemenu'}</label>
                        <select name="column_type"  onChange="javascript:changeColumnType({$column->id_tab|intval}, {$column->id|intval}, $(this).val());">
                                <option value="{$all_types.list}" 
                                        {if $column->type == $all_types.list}selected{/if}>{l s='List' mod='innovativemenu'}</option>
                                <option value="{$all_types.text}" 
                                        {if $column->type == $all_types.text}selected{/if}>{l s='Text' mod='innovativemenu'}</option>
                                {if $tab->type == 'categories'}
                                <option value="{$all_types.categories}" 
                                        {if $column->type == $all_types.categories}selected{/if}>{l s='Categories' mod='innovativemenu'}</option>
                                {/if}
                        </select>
                        <div class='margin-form'>{l s='The column can be a personalized text or a block of lines' mod='innovativemenu'}</div>
                </div>
                <div class="clear">&nbsp;</div>

                <div class="innovative_div innovative_column_content_{$column->id|intval}">
                                {$innovative_column_content}
                </div>
                <div class="clear">&nbsp;</div>
                
                
                <div class='innovative_div'>
                        <label>{l s='Column with title ?' mod='innovativemenu'}</label>
                        <input type="checkbox" name="column_with_title" onChange="javascript:toggleConfigTitleOfColumn(this, {$column->id|intval});"
                                {if $column->with_title}value="1" checked="checked"{else}value="0"{/if}/>
                        <div class='margin-form'>{l s='Does the column have to have a title ?' mod='innovativemenu'}</div>
                </div>
                <div class="clear">&nbsp;</div>
                    
                <div class="innovative_column_{$column->id}_title innovative_div" style="display:{if $column->with_title}block{else}none{/if};">
                        <label>{l s='title' mod='innovativemenu'}</label>
                        {foreach from=$languages item=language}
                        <div id="column-{$column->id}-title_{$language.id_lang}" class="multilangs_fields" style="float:left; display:{if $default_language == $language.id_lang}block{else}none{/if};">
                                {assign var='id_lang' value=$language.id_lang}
                                <input type="text" name="column_title_{$language.id_lang|intval}" value="{if isset($column->title.$id_lang)}{Tools::htmlentitiesUTF8($column->title.$id_lang)}{/if}" size="60"/>
                        </div>
                        {/foreach}
                        {$display_flags}
                        <br />
                        <div class='margin-form'>{l s='Edit the title of this column' mod='innovativemenu'}</div>
                </div>
                <div class="clear">&nbsp;</div>
                
                <div class='innovative_div' style="display:{if $tab->advanced_config}block{else}none{/if};">
                        <label>{l s='Active advanced config ?' mod='innovativemenu'}</label>
                        <input type="checkbox" name="column_advanced_config" onChange="javascript:toggleAdvancedConfiguration(this, '.column_with_advanced_config_{$column->id|intval}')"
                                {if $column->advanced_config}value="1" checked="checked"{else}value="0"{/if}/>
                        <div class='margin-form'>{l s='If this option is marked, every column will have its style ?' mod='innovativemenu'}</div>
                </div>
                <div class="clear">&nbsp;</div>
                
                <div id="configure_column_with_title_{$column->id|intval}" style="display:{if $column->with_title}block{else}none{/if}">
                        <div class='innovative_div'>
                                <label>{l s='title clickable ?' mod='innovativemenu'}</label>
                                <input type="checkbox" name="column_title_clickable" onChange="javascript:toggleConfigTitleClickable(this, {$column->id|intval})"
                                        {if $column->title_clickable}value="1" checked="checked"{else}value="0"{/if}/>
                                <div class='margin-form'>{l s='Does the title of this column have to have a link ?' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                          
                        <div  {if $column->type == 'categories'}style="display:none;"{/if}>
                                <div class="innovative_column_{$column->id}_title_link innovative_div" style="display:{if $column->title_clickable}block{else}none{/if};">
                                                <label>{l s='Link of title' mod='innovativemenu'}</label>
                                                <input type="text" value="{$column->title_link|htmlentities}" name="column_title_link" size="60"/>
                                                <div class='margin-form'>{l s='Edit the link of the title' mod='innovativemenu'}</div>
                                        </div>
                                </div>
                        </div>
                                
                        <div class="clear">&nbsp;</div>
                        
                        <div class='column_with_advanced_config_{$column->id|intval}' style='display:{if $tab->advanced_config && $column->advanced_config}block{else}none{/if}'>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                                <label>{l s='With horizontal line ?' mod='innovativemenu'}</label>
                                                <input type='checkbox' name="column_title_with_horizontal_line" value='1' {if $column->title_with_horizontal_line}checked{/if}
                                                              onClick='javascript:toggleDiv($(this), $(".config_vertical_line"))'/>
                                                <div class='margin-form'>{l s='Title and content of columns separate with horizontal line ?' mod='innovativemenu'}</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>
                                <div class="clear">&nbsp;</div>
                                <div class='config_vertical_line' style='display:{if $column->title_with_horizontal_line}block{else}none{/if}'>
                                        <div class='innovative_div'>
                                                <label>{l s='Line width' mod='innovativemenu'}</label>
                                                <input type='text' name='column_title_horizontal_line_width' size="6" maxlength="6"
                                                       value='{$column->title_horizontal_line_width}'>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>

                                                <label>{l s='Line color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="column_title_horizontal_line_color"
                                                        value="{$column->title_horizontal_line_color|htmlentities}"
                                                        style="background-color:#{$column->title_horizontal_line_color|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='With vertical line ?' mod='innovativemenu'}</label>
                                        <input type='checkbox' name="column_with_border_left" value='1' {if $column->with_border_left}checked{/if}
                                              onClick='javascript:toggleDiv($(this), $(".config_horizontal_line"))'/>
                                        <div class='margin-form'>{l s='Columns separate by vertical line ?' mod='innovativemenu'}</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='config_horizontal_line' style='display:{if $column->with_border_left}block{else}none{/if}'>
                                        <div class='innovative_div'>
                                                <label>{l s='Line width' mod='innovativemenu'}</label>
                                                <input type='text' name='column_border_left_width' size="6" maxlength="6"
                                                       value='{$column->border_left_width}'>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                        <div class="clear">&nbsp;</div>

                                        <div class='innovative_div'>

                                                <label>{l s='Line color' mod='innovativemenu'}</label>
                                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="column_border_left_color"
                                                        value="{$column->border_left_color|htmlentities}"
                                                        style="background-color:#{$column->border_left_color|htmlentities}"/>
                                                <div class='margin-form'>&nbsp;</div>
                                        </div>
                                </div>
                                <div class="clear">&nbsp;</div>
                                        
                                <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Title setting' mod='innovativemenu'}</div>
                                <div class="clear">&nbsp;</div>
                                
                                <div class='innovative_div'>
                                        <label>{l s='Font color' mod='innovativemenu'}</label>
                                        <input size="6" maxlength="6" class="my_colorpicker" type="text" name="column_title_font_color"
                                                value="{$column->title_font_color|htmlentities}"
                                                style="background-color:#{$column->title_font_color|htmlentities}"/>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font size' mod='innovativemenu'}</label>
                                        <select name="column_title_font_size">
                                                {$select_title_font_size}
                                        </select> em
                                        <div class='margin-form'>{l s='Edit the size of this title. This size is relative, according to the size of the text.' mod='innovativemenu'}</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font weight' mod='innovativemenu'}</label>
                                        <select name="column_title_font_weight">
                                                {$select_title_font_weight}
                                        </select>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font style' mod='innovativemenu'}</label>
                                        <select name="column_title_font_style">
                                                {$select_title_font_style}
                                        </select>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>

                                <div class='innovative_div'>
                                        <label>{l s='Font family' mod='innovativemenu'}</label>
                                        <select name="column_title_font_family">
                                                {$select_title_font_family}
                                        </select>
                                        <div class='margin-form'>&nbsp;</div>
                                </div>
                                <div class="clear">&nbsp;</div>
                        </div>
                </div>
                                        
                                
                <div class='column_with_advanced_config_{$column->id|htmlentities}' style='display:{if $tab->advanced_config && $column->advanced_config}block{else}none{/if}'>
                        <div class="clear">&nbsp;</div>
                                        
                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Title setting' mod='innovativemenu'}</div>
                        <div class="clear">&nbsp;</div>
                        <div class='innovative_div'>
                                <label>{l s='Font color' mod='innovativemenu'}</label>
                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="column_font_color"
                                        value="{$column->font_color|htmlentities}"
                                        style="background-color:#{$column->font_color|htmlentities}"/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font size' mod='innovativemenu'}</label>
                                <select name="column_font_size">
                                        {$select_font_size}
                                </select> px
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font weight' mod='innovativemenu'}</label>
                                <select name="column_font_weight">
                                        {$select_font_weight}
                                </select>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font style' mod='innovativemenu'}</label>
                                <select name="column_font_style">
                                        {$select_font_style}
                                </select>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font family' mod='innovativemenu'}</label>
                                <select name="column_font_family">
                                        {$select_font_family}
                                </select>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Width' mod='innovativemenu'}</label>
                                <input name="column_width" type="text" size="3" value="{$column->width|intval}"/> px
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        <div class="hint" style="display:block; font-weight: bold; z-index: 0;">{l s='Configuration of lists in columns when mouse is over' mod='innovativemenu'}</div>
                        <div class="clear">&nbsp;</div>
                        <div class='innovative_div'>
                                <label>{l s='Font color hover' mod='innovativemenu'}</label>
                                <input size="6" maxlength="6" class="my_colorpicker" type="text" name="column_list_font_color_hover"
                                        value="{$column->list_font_color_hover|htmlentities}"
                                        style="background-color:#{$column->list_font_color_hover|htmlentities}"/>
                                <div class='margin-form'>{l s='Font color of list in columns when mouse is over.' mod='innovativemenu'}</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font style' mod='innovativemenu'}</label>
                                <select name="column_list_font_style_hover">
                                        {$select_list_font_style_hover}
                                <select/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>

                        <div class='innovative_div'>
                                <label>{l s='Font weight' mod='innovativemenu'}</label>
                                <select name="column_list_font_weight_hover">
                                        {$select_list_font_weight_hover}
                                <select/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                        <div class="clear">&nbsp;</div>
                        
                        <div class='innovative_div'>
                                <label>{l s='Text underline' mod='innovativemenu'}</label>
                                <input type='checkbox' name='column_list_underline_hover' {if $column->list_underline_hover}checked{/if}/>
                                <div class='margin-form'>&nbsp;</div>
                        </div>
                </div>

                <div class='bo_center'>
                        <button class="button" type="button" onClick="javascript:saveColumn({$tab->id|intval}, {$column->id|intval})">
                                {l s='Save column' mod='innovativemenu'}
                        </button>
                </div>
        </form>
</fieldset>               
{elseif isset($edit_ads)}
<fieldset>
        <legend>{l s='Add advertising' mod='innovativemenu'}</legend>
        <form class="innovatimemenu_ads_{if $ads->id}{$ads->id|intval}{else}0{/if}">
                <label>{l s='Title' mod='innovativemenu'}</label>
                {foreach from=$languages item=language}
                        {assign var='id_lang' value=$language.id_lang}
                        <div id="title_{$language.id_lang|intval}" class="multilangs_fields" style="float:left; display:{if $default_language == $language.id_lang}block{else}none{/if};">
                                <input type="text" name="ads_title_{$language.id_lang|intval}" value="{if $ads->id|intval}{Tools::htmlentitiesUTF8($ads->title.$id_lang)}{/if}" size="60" />
                        </div>
                {/foreach}
                {$display_flags.title}
                
                <div class="clear">&nbsp;</div>
                <label>{l s='Content' mod='innovativemenu'}</label>
                {foreach from=$languages item=language}
                        <div id="content_{$language.id_lang|intval}" class="multilangs_fields" style="display:{if $default_language == $language.id_lang}block{else}none{/if};">
                                <textarea class="rte" name="ads_content_{$language.id_lang|intval}">
                                        {assign var='id_lang' value=$language.id_lang}
                                        {if $ads->id}{InnovativeMenu::cleanHTML($ads->content.$id_lang)}{/if}
                                </textarea>
                        </div>
                {/foreach}
                {$display_flags.content}
                <div class="clear">&nbsp;</div>
                <input type="hidden" value="{$id_tab}" name="id_tab"/>
                <input type="hidden" value="{$id_ads}" name="id_ads"/>
                <div class='bo_center'>
                        <button type="button" class="button" onclick="javascript:saveAds({$id_tab|intval}{if $ads->id},{$ads->id|intval}{/if});">
                                {l s='Save advertising' mod='innovativemenu'}
                        </button>
                </div>
                <div class="clear">&nbsp;</div>
        </form>
</div>
{/if}
