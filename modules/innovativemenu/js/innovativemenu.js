$.ajaxSetup({url : url, type: 'POST', crossDomain : true});

var show_innovativemenu_activity;
$(document).ajaxStart(function(){
        $('.notification').html('');
        if (show_innovativemenu_activity)
        {
                $.fancybox.showActivity();
        }
});

$(document).ajaxComplete(function(){
        if (show_innovativemenu_activity)
        {
                initAll();
                $.fancybox.hideActivity();
                show_innovativemenu_activity = false;
        }
        
});

function toggleActive(_type, id)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'toggleActive', id : id, type : _type},
                success: function(msg){
                                if (_type == 'menu')
                                        displayAllMenus();
                                else{
                                        el = '#active_'+_type+'_'+id;
                                        html = $(el).html();
                                        if (html.search('enabled'))
                                                html = html.replace('enabled', 'disabled');
                                        else if (html.search('disabled'))
                                                html = html.replace('disabled', 'enabled');
                                        $(el).html(html);
                                }
                        }
        });
}



function toggleMenuGeneralConfiguration(el)
{
        if ($(el).attr('checked'))
                $('#general_configuration').show();
        else $('#general_configuration').hide();
}


function toggleDiv(checkBox, div)
{
        if (checkBox.attr('checked'))
                div.show();
        else div.hide();
}

function toggleWithHomeTab(el)
{
        if ($(el).attr('checked'))
                $('#with_home_tab').show();
        else $('#with_home_tab').hide();
}

function saveLink()
{
        myData = $("form.innovative_new_link").serializeArray();
        json = {};
        for(i in myData)
        {
                json[myData[i].name] = myData[i].value;
        }
        
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'saveLink', link_data : json},
                success: function(msg){
                        if(msg)
                                $("form.innovative_new_link").html(msg);
                                getLinks();
                        }
        });
}


function saveFont(id_font)
{
        myData = $("form.innovative_new_font").serializeArray();
        json = {};
        for(i in myData)
        {
                json[myData[i].name] = myData[i].value;
        }
        
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'saveFont', font_data : json, id_font : id_font},
                success: function(msg){
                        if(msg)
                                $("form.innovative_new_font").html(msg);
                                getFonts();
                        }
        });
}



function getFonts()
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'getFonts'},
                success: function(msg){
                        if(msg)
                                $(".innovative_all_fonts").html(msg);
                        }
        });
}



function getLinks()
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'getLinks'},
                success: function(msg){
                        if(msg)
                                $(".innovative_all_links").html(msg);
                        }
        });
}



function editFont(idFont)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editFont', id_font : idFont},
                success: function(msg){
                        $('form.innovative_new_font').html(msg);
                        }
        });
}


function deleteFont(idFont)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'deleteFont', id_font : idFont},
                success: function(msg){
                        getFonts();
                        }
        });
}



function editLink(idLink)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editLink', id_link : idLink},
                success: function(msg){
                        $('form.innovative_new_link').html(msg);
                        }
        });
}


function deleteLink(idLink)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'deleteLink', id_link : idLink},
                success: function(msg){
                        getLinks();
                        }
        });
}


function updateMenus()
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'getMenus', id_element : parseInt(id_element), type_context : parseInt(type_context)},
                success: function(msg){
                        $('.all_menus').html(msg);
                        }
        });      
}


function deleteMenu(idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'deleteMenu', id_menu : parseInt(idMenu)},
                success: function(msg){
                        $('.configure_menu').html('');
                        $('.all_menus').html(msg);
                        $("div.view_menu").html('');
                        }
        });
}

function editMenu(idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editMenu', id_menu : idMenu},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $("div.configure_menu").html(msg.configure);

                        if(parseInt(idMenu))
                                $("div.view_menu").html(msg.view);
                        else $("div.view_menu").html('');
                        }
        });
}


function saveMenu(idMenu, name, value, colorpicker)
{
        json = {};

        myData = $("form.menu_data").serializeArray();
        for(i in myData)
        {
                json[myData[i].name] = myData[i].value;
        }
  
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'saveMenu', id_menu : parseInt(idMenu), id_element : parseInt(id_element), type_context: parseInt(type_context), menu_data : json},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $("div.configure_menu").html(msg.configure);
                        $("div.view_menu").html(msg.view);
                        $('.all_menus').html(msg.all);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                        }
        });
}


function viewMenu(idMenu)
{
        idMenu = parseInt(idMenu);
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'viewMenu', id_menu : parseInt(idMenu)},
                success: function(msg){
                        $("div.view_menu").html(msg);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                        }
        });
}

function editTab(idMenu, idTab)
{
        idMenu = parseInt(idMenu);
        idTab = parseInt(idTab);
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editTab', id_menu : idMenu, id_tab : idTab},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        idTab = parseInt(msg.id_tab);
                        htmlParent = $(".innovative_edit_tab").html();
                        if($(".innovative_edit_tab").children('#innovative_tab_fieldset_'+idTab).html())
                                $('#innovative_tab_fieldset_'+idTab).html(msg.configure);
                        else
                        {
                                $(".innovative_edit_tab").children('fieldset').each(function(){
                                        $(this).children('.header_module2').each(function(){
                                                element_id = $(this).attr('id');
                                                if ($('#'+element_id+'_content').css('display') != 'none')
                                                        toggleBlock(element_id);
                                        });
                                        
                                });
                                $(".innovative_edit_tab").prepend('<div class="clear">&nbsp;</div><fieldset id="innovative_tab_fieldset_'+idTab+'">'+msg.configure+'</fieldset>');
                        }
                        $('.innovative_all_tabs_'+parseInt(idMenu)).html(msg.all);
                }
        });
}

function saveTab(idMenu, idTab, table)
{
        json = {};
        idMenu = parseInt(idMenu);
        idTab = parseInt(idTab);
        if (table)
        {
                $('#innovative_list_tab_'+idTab+' > td').children('input').each(function(){
                        json[$(this).attr('name')] = $(this).val();
                });
                json['id_menu'] = idMenu;
                json['id_tab'] = idTab;
        }
        else
        {
                myData = $('form#innovative_tab_form_'+idTab).serializeArray();
                for (i in myData)
                {
                        json[myData[i].name] = myData[i].value;
                }
        }
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'saveTab', id_menu : idMenu, id_tab : idTab, tab_data : json},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $('#innovative_tab_fieldset_0').remove();
                        $('#innovative_tab_fieldset_'+idTab).html(msg.configure);
                        $('.innovative_all_tabs_'+parseInt(idMenu)).html(msg.all);
                        $("div.view_menu").html(msg.view_menu);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                        }
        });
}


function deleteTab(idTab, idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'deleteTab', id_tab : parseInt(idTab)},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        //$('#innovative_list_tab_'+parseInt(idTab)).remove();
                        $('#innovative_tab_fieldset_'+idTab).remove();
                        $('.innovative_all_tabs_'+parseInt(idMenu)).html(msg.all);
                        $("div.view_menu").html(msg.view_menu);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                        }
        });
}


function editAds(idTab, idAds)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editAds', id_tab : parseInt(idTab), id_ads : parseInt(idAds)},
                success: function(msg){
                        $(".innovative_edit_ads").html(msg);
                        }
        });
}


function saveAds(idTab, idAds)
{
        tinyMCE.triggerSave();
        if(!idAds)
                idAds = 0;
        myData = $('form.innovatimemenu_ads_'+parseInt(idAds)).serializeArray();
        
        
        json = {};
        for (i in myData)
                {
                        json[myData[i].name] = myData[i].value;
                }
                
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'saveAds', id_tab : parseInt(idTab), id_ads : parseInt(idAds), ads_data : json},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $('.innovative_all_ads_'+parseInt(idTab)).html(msg.all);
                        $("div.view_menu").html(msg.view_menu);
                        $(".innovative_edit_ads").html('');
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                }
        });
}


function deleteAds(idAds, idTab)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'deleteAds', id_ads : parseInt(idAds)},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $('#innovative_list_ads_'+parseInt(idAds)).remove();
                        $('.innovative_all_ads_'+parseInt(idTab)).html(msg.all);
                        $('div.view_menu').html(msg.view_menu);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                }
        });
}


function moveTabPosition(idMenu, idTab, direction)
{
        if (direction == 'up' | direction == 'down')
        {
                show_innovativemenu_activity = true;
                $.ajax({
                        data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'moveTabPosition', id_tab : parseInt(idTab), direction : direction},
                        success: function(msg){
                                msg = $.parseJSON(msg);
                                $('.innovative_all_tabs_'+parseInt(idMenu)).html(msg.all_tabs);
                                $("div.view_menu").html(msg.view_menu);
                                $.scrollTo( '.view_menu', 800, {queue:true} );
                                }
                });
        }
}


function moveColumnPosition(idTab, idColumn, direction)
{
        if (direction == 'up' | direction == 'down')
        {
                show_innovativemenu_activity = true;
                $.ajax({
                        data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'moveColumnPosition', id_column : parseInt(idColumn), direction : direction},
                        success: function(msg){
                                msg = $.parseJSON(msg);
                                $('.innovative_all_columns_'+parseInt(idTab)).html(msg.all_columns);
                                $("div.view_menu").html(msg.view_menu);
                                $.scrollTo( '.view_menu', 800, {queue:true} );
                                }
                });
        }
}


function moveAdsPosition(idTab, idAds, direction)
{
        if (direction == 'up' | direction == 'down')
        {
                show_innovativemenu_activity = true;
                $.ajax({
                        data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'moveAdsPosition', id_ads : parseInt(idAds), direction : direction},
                        success: function(msg){
                                msg = $.parseJSON(msg);
                                $('.innovative_all_ads_'+parseInt(idTab)).html(msg.all_ads);
                                $("div.view_menu").html(msg.view_menu);
                                $.scrollTo( '.view_menu', 800, {queue:true} );
                                }
                });
        }
}

function updateTabsOfMenu(idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'getTabsOfMenu', id_menu : parseInt(idMenu)},
                success: function(msg){
                        $('.innovative_all_tabs_'+parseInt(idMenu)).html(msg);
                        }
        });
}



function editTabType(type, idMenu, idTab)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editTabType', id_menu : parseInt(idMenu), id_tab : parseInt(idTab), type : type},
                success: function(msg){
                        $(".configure_tabs_content_"+idTab).html(msg);
                        }
        });
}


function editColumn(idTab, idCol)
{
        if(!idCol)
                idCol = 0;
        
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'editColumn', id_tab : parseInt(idTab), id_column : parseInt(idCol)},
                success: function(msg){
                        $(".innovative_edit_column").html(msg);
                        }
        });
}


function saveColumn(idTab, idColumn)
{
        $('.element_removable').each(function(){$(this).attr('selected', 'selected')});
        tinyMCE.triggerSave();
        myData = $("form#innovative_column_form_"+parseInt(idColumn)).serializeArray();

        json = {};
        for(i in myData)
        {
                if(myData[i].name == 'column_content_'+idColumn)
                {
                        j = 0;
                        json2 = {};
                        $('#column_content_'+idColumn).children(".element_removable").each(function(){
                                json2[j] = $(this).val();
                                j++;
                        });
                        json[myData[i].name] = json2;
                }
                else json[myData[i].name] = myData[i].value;
        }
        
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'saveColumn', id_tab : parseInt(idTab), id_column : parseInt(idColumn), column_data : json},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $('.innovative_all_columns_'+parseInt(idTab)).html(msg.all);
                        $("div.view_menu").html(msg.view_menu);
                        $(".innovative_edit_column").html(msg.configure);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                }
        });
}

function deleteColumn(idColumn, idTab)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'deleteColumn', id_column : parseInt(idColumn)},
                success: function(msg){
                        msg = $.parseJSON(msg);
                        $('#innovative_list_column_'+parseInt(idColumn)).remove();
                        $('.innovative_all_columns_'+parseInt(idTab)).html(msg.all);
                        $('div.view_menu').html(msg.view_menu);
                        $.scrollTo( '.view_menu', 800, {queue:true} );
                }
        });
}

function updateColumnsOfTab(idTab, idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'getColumnsOfTab', id_tab : parseInt(idTab)},
                success: function(msg){
                        $('.innovative_all_columns_'+parseInt(idTab)).html(msg);
                        viewMenu(idMenu);
                        }
        });
}



function updateAdsOfTab(idTab, idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'getAdsOfTab', id_tab : parseInt(idTab)},
                success: function(msg){
                        $('.innovative_all_ads_'+parseInt(idTab)).html(msg);
                        viewMenu(idMenu);
                        }
        });
}



function changeColumnCategory(idTab, idColumn, idCategory)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'changeColumnCategory', id_tab : parseInt(idTab), id_column : parseInt(idColumn), idCategory : idCategory},
                success: function(msg){
                        $('#innovative_column_'+parseInt(idColumn)+'_category').html(msg);
                        $('#column_content_'+parseInt(idColumn)).children('.element_removable').each(function(){
                                $(this).remove();
                        });
                }
        });
}


function changeColumnType(idTab, idColumn, value)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'changeColumnType', id_tab : parseInt(idTab), id_column : parseInt(idColumn), value : value},
                success: function(msg){
                        $('.innovative_column_content_'+parseInt(idColumn)).html(msg);
                        }
        });
}



function displayAllMenus()
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'allMenus', id_element : parseInt(id_element), type_context : parseInt(type_context)},
                success: function(msg){
                        $(".all_menus").html(msg);
                        }
        });
}


function previewMenu(idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'previewMenu', idMenu : idMenu},
                success: function(msg){
                        $(".preview_menu").html(msg);
                        }
        });
}


function configureMenu(idMenu)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'configureMenu', idMenu : idMenu},
                success: function(msg){
                        $(".configure_menu").html(msg);
                        }
        });
}

function setColorPicker()
{
        $('.my_colorpicker').ColorPicker({
                onSubmit: function(hsb, hex, rgb, el) {
                        $(el).val(hex);
                        $(el).ColorPickerHide();
                        $(el).css('background-color', '#'+hex);
                },
                onBeforeShow: function () {
                        $(this).ColorPickerSetColor(this.value);
                        $(this).css('background-color', '#'+this.value);
                        /*colorpicker_on = true;*/
                }
        })
        .bind('keyup', function(){
                $(this).ColorPickerSetColor(this.value);
                $(this).css('background-color', '#'+this.value);               
        });
}


function toggleBlock2(el)
{
        if(!$('#'+el).hasClass('active'))
        {
                active_id = $('#'+el).parent().children('.active').removeClass('active').attr('id');
                $('#'+active_id+'_content').hide(100);
                $('#'+el).addClass('active');
                $('#'+el+'_content').show(100);
                
        }
}

function toggleBlock(el)
{
        html = $('#'+el).html();
        if(html.search('less.png') > 0)
        {
                html = html.replace('less.png', 'more.png');
                $('#'+el).html(html);
                $('#'+el+'_content').hide(100);
        }
        else if(html.search('more.png') > 0)
        {
                html = html.replace('more.png', 'less.png');
                $('#'+el).html(html);
                $('#'+el+'_content').show(100);
        }
}


function loadTab(tabName)
{
        el = $('#'+tabName);
        if (el.hasClass('bo_top_tabs_2'))
        {
                show_innovativemenu_activity = true;
                $.ajax({
                        data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'loadBOTabs', id_element : parseInt(id_element), type_context : parseInt(type_context), tab : tabName},
                        success: function(msg){
                                        $('.bo_bottom').html(msg);
                                        el.parent().children('.bo_top_tabs').each(function(){$(this).addClass('bo_top_tabs_2')});
                                        el.removeClass('bo_top_tabs_2');
                                }
                });
        }
}



function addElementOnColumn(el, idTab, idColumn)
{
        val = $(el).val();
        text = $(el).text();
        result = '<option value="'+val+'" class="element_removable" ondblclick="javascript:removeElementOnColumn('+idColumn+', this);">'+text+'</option>';
        $('#column_content_'+parseInt(idColumn)).append(result);
}



function removeElementOnColumn(idColumn, el)
{
        show_innovativemenu_activity = true;
        $.ajax({
                data: {employee_id_lang : employee_id_lang, token : token, innovativemenu_action : 'removeElementOnColumn', id_column : idColumn, data_element : $(el).attr("value")},
                success: function(msg){
                                $(el).remove();
                                $(el).attr("value");
                        }
        });        
}


function toggleWithAds(el, idTab)
{
        if($(el).attr('checked'))
                $('.tab_with_ads_'+parseInt(idTab)).show();
        else $('.tab_with_ads_'+parseInt(idTab)).hide();
        
}


function toggleConfigTitleOfColumn(el, idColumn)
{
        if($(el).val() == 0)
        {
                $('#configure_column_with_title_'+parseInt(idColumn)).show(100);
                $(el).val(1);
                $('.innovative_column_'+idColumn+'_title').show(100);
        }
        else
        {
                $('#configure_column_with_title_'+parseInt(idColumn)).hide(100);
                $(el).val(0);
                $('.innovative_column_'+idColumn+'_title').hide(100);
        }
        
}



function toggleConfigTitleClickable(el, idColumn)
{
        if($(el).val() == 0)
        {
                $(el).val(1);
                $('.innovative_column_'+idColumn+'_title_link').show(100);
        }
        else
        {
                $(el).val(0);
                $('.innovative_column_'+idColumn+'_title_link').hide(100);
        }
        
}


function toggleAdvancedConfiguration(el, _class)
{
        if($(el).val() == 0)
        {
                $(_class).show(100);
                $(el).val(1);
        }
        else
        {
                $(_class).hide(100);
                $(el).val(0)
        }
        
}



function initTinyMCE(){
        tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		skin:"cirkuit",
		editor_selector : "rte",
		editor_deselector : "noEditor",
		plugins : "safari,pagebreak,style,table,advimage,advlink,inlinepopups,media,contextmenu,paste,fullscreen,xhtmlxtras,preview",
		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "styleprops,|,cite,abbr,acronym,del,ins,attribs,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
        content_css : pathCSS+"global.css",
		document_base_url : ad,
		width: "200px",
		height: "auto",
		font_size_style_values : "8pt, 10pt, 12pt, 14pt, 18pt, 24pt, 36pt",
		elements : "nourlconvert,ajaxfilemanager",
		file_browser_callback : "ajaxfilemanager",
		entity_encoding: "raw",
		convert_urls : false,
        language : iso
		
	});
}

function my_changeLanguage(el, id_language)
{
        $('.multilangs_fields').hide();
        $(".multilangs_fields").each(function(){
                id = $(this).attr("id");
                tmp = id.split('_');

                if (tmp[tmp.length - 1] == id_language)
                {
                        $('#'+id).show();
                        id = id.replace('_'+id_language, '');
                        changeLanguage(id, id, id_language, '');
                }

        });
}


function initFileLoader()
{
        $(function() {
                
                $('#ajax-upload-2').ajaxUpload({
                        url: url_upload,
                        disableOnSubmit: false,
                        allowedExtensions: ['zip']
                });

                // Make use global event "uploaded"
                $('#uploadedFiles').bind("uploaded",
                        function(e, id, filename, json) {
                                $(this).append("<li>" + filename + "</li>");
                        });

                $('#attachBtn').attachBtn({uploadUrl: url_upload, deleteUrl: url_upload});
        });
}


function initAll()
{
        initTinyMCE();
        setColorPicker();
        initFileLoader();
}

