<?php
        
$query = array();

$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu`(
        `id_menu` INTEGER NOT NULL AUTO_INCREMENT,
        `id_menu_context` INTEGER,
        `name` VARCHAR(32) NOT NULL,
        `background_color` VARCHAR(32),
        `font_size` INTEGER,       
        `font_family` VARCHAR(254),
        `font_style` VARCHAR(254),
        `font_weight` VARCHAR(254),
        `font_color` VARCHAR(32),
        `font_color_hover` VARCHAR(32),
        `general_configuration` TINYINT(1) NOT NULL,
        `border_top_radius` INTEGER,
        `border_bottom_radius` INTEGER,
        `radial_gradient` TINYINT(1) NOT NULL,
        `column_title_font_size` FLOAT,       
        `column_title_font_family` VARCHAR(254),
        `column_title_font_style` VARCHAR(254),
        `column_title_font_weight` VARCHAR(254),
        `column_title_font_color` VARCHAR(32),
        `column_title_underline` TINYINT(1) NOT NULL,
        `column_title_horizontal_line_width` INTEGER,
        `column_title_horizontal_line_color` VARCHAR(32),
        `column_title_with_horizontal_line` TINYINT(1),
        `column_width` INTEGER,
        `column_border_left_width` INTEGER,
        `column_border_left_color` VARCHAR(32),
        `column_with_border_left` TINYINT(1),
	`column_list_font_style_hover` VARCHAR(254),
        `column_list_font_weight_hover` VARCHAR(254),
	`column_list_font_color_hover` VARCHAR(32),
        `column_list_underline_hover` TINYINT(1) NOT NULL,
        `tab_background_color` VARCHAR(32),
        `tab_background_color_hover` VARCHAR(32),
        `block_background_color` VARCHAR(32),
        `block_border_color` VARCHAR(32),
        `block_border_width` INTEGER,
        `block_font_color` VARCHAR(32),
        `block_font_size` INTEGER,
        `block_font_style` VARCHAR(254),
        `block_font_weight` VARCHAR(254),
        `block_font_family` VARCHAR(254),
        `with_home_tab` TINYINT(1) NOT NULL,
        `height` INTEGER,
        `width` INTEGER,
        `active` TINYINT(1) NOT NULL,
        `date_add` TIMESTAMP,
        `date_upd` TIMESTAMP,
PRIMARY KEY(`id_menu`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_lang`(
        `id_menu` INTEGER NOT NULL,
        `id_lang` INTEGER NOT NULL,
        `home_text` VARCHAR(254),
KEY(`id_menu`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_tab`(
        `id_tab` INTEGER NOT NULL AUTO_INCREMENT,
        `id_menu` INTEGER NOT NULL,
        `position` INTEGER,
        `font_family` VARCHAR(254),
        `font_weight` VARCHAR(254), 
        `font_style` VARCHAR(254),
        `font_size` INTEGER,
        `ads_align` VARCHAR(254),
        `ads_width` INTEGER,
        `ads_font_color` VARCHAR(32),
        `ads_background_color` VARCHAR(32),
        `with_ads` TINYINT(1) NOT NULL,
        `column_title_font_family` VARCHAR(254),
        `column_title_font_weight` VARCHAR(254), 
        `column_title_font_style` VARCHAR(254),
        `column_title_font_size` FLOAT,
        `column_title_font_color` VARCHAR(32),
        `column_title_horizontal_line_width` INTEGER,
        `column_title_horizontal_line_color` VARCHAR(32),
        `column_title_with_horizontal_line` TINYINT(1),
        `column_title_underline` TINYINT(1) NOT NULL,
        `column_font_family` VARCHAR(254),
        `column_font_weight` VARCHAR(254), 
        `column_font_style` VARCHAR(254),
        `column_font_size` INTEGER,
        `column_font_color` VARCHAR(32),
        `column_border_left_width` INTEGER,
        `column_border_left_color` VARCHAR(32),
        `column_with_border_left` TINYINT(1),
	`column_list_font_style_hover` VARCHAR(254),
        `column_list_font_weight_hover` VARCHAR(254),
	`column_list_font_color_hover` VARCHAR(32),
        `column_list_underline_hover` TINYINT(1) NOT NULL,
        `id_link` VARCHAR(254),
        `type` VARCHAR(254),
        `font_color` VARCHAR(32),
        `font_color_hover` VARCHAR(32),
        `background_color` VARCHAR(32),
        `background_color_hover` VARCHAR(32),
        `block_background_color` VARCHAR(32),
        `block_border_color` VARCHAR(32),
        `block_border_width` INTEGER,
        `active` TINYINT(1) NOT NULL DEFAULT 1,
        `advanced_config` TINYINT(1) NOT NULL DEFAULT 0,
        `column_width` INTEGER,
        `date_add` TIMESTAMP,
        `date_upd` TIMESTAMP,
PRIMARY KEY(`id_tab`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_tab_lang`(
        `id_tab` INTEGER NOT NULL,
        `id_lang` INTEGER NOT NULL,
        `name` VARCHAR(254),
KEY(`id_tab`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_column`(
        `id_column` INTEGER NOT NULL AUTO_INCREMENT,
        `id_tab` INTEGER NOT NULL,
        `position` INTEGER NOT NULL DEFAULT 0,
        `active` TINYINT(1) NOT NULL DEFAULT 1,
        `with_title` TINYINT(1) NOT NULL DEFAULT 0,
        `title_clickable` TINYINT(1) NOT NULL DEFAULT 0,
        `advanced_config` TINYINT(1) NOT NULL DEFAULT 0,
        `type` VARCHAR(254),
        `id_type` INTEGER,
        `font_color` VARCHAR(32),
        `font_weight` VARCHAR(32),
        `font_style` VARCHAR(32),
        `font_family` VARCHAR(254),
        `font_size` INTEGER,
        `title_link` VARCHAR(254),
        `title_font_color` VARCHAR(32),
        `title_font_weight` VARCHAR(32),
        `title_font_style` VARCHAR(32),
        `title_font_family` VARCHAR(254),
        `title_font_size` FLOAT,
        `title_underline` TINYINT(1) NOT NULL,
        `title_horizontal_line_width` INTEGER,
        `title_horizontal_line_color` VARCHAR(32),
        `title_with_horizontal_line` TINYINT(1),
	`list_font_style_hover` VARCHAR(254),
        `list_font_weight_hover` VARCHAR(254),
	`list_font_color_hover` VARCHAR(32),
        `list_underline_hover` TINYINT(1) NOT NULL,
        `border_left_width` INTEGER,
        `border_left_color` VARCHAR(32),
        `with_border_left` TINYINT(1),
        `width` INTEGER,
        `date_add` TIMESTAMP,
        `date_upd` TIMESTAMP,
PRIMARY KEY(`id_column`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_column_content`(
        `id_column` INTEGER NOT NULL,
        `content_id` INTEGER NOT NULL,
        `tab_type` VARCHAR(32),
        `column_type` VARCHAR(32),
KEY(`id_column`, `content_id`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_column_lang`(
        `id_column` INTEGER NOT NULL,
        `id_lang` INTEGER NOT NULL,
        `text` TEXT,
        `title` VARCHAR(254),
KEY(`id_column`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_personalized_link`(
        `id_link` INTEGER NOT NULL AUTO_INCREMENT,
        `link` VARCHAR(254),
        `id_menu_context` INTEGER,
PRIMARY KEY(`id_link`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_personalized_link_lang`(
        `id_link` INTEGER NOT NULL,
        `id_lang` INTEGER NOT NULL,
        `name` VARCHAR(254),
KEY(`id_link`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_ads`(
        `id_ads` INTEGER NOT NULL AUTO_INCREMENT,
        `id_tab` INTEGER NOT NULL,
        `width` INTEGER NOT NULL,
        `position` INTEGER NOT NULL,
        `active` TINYINT(1) NOT NULL DEFAULT 1,
PRIMARY KEY(`id_ads`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_ads_lang`(
        `id_ads` INTEGER NOT NULL,
        `id_lang` INTEGER NOT NULL,
        `content` TEXT,
        `title` VARCHAR(254),
KEY(`id_ads`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_font_family`(
        `id_font` INTEGER NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(254) NOT NULL,
        `alt_name1` VARCHAR(254) NOT NULL,
        `alt_name2` VARCHAR(254) NOT NULL,
        `with_file` TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY(`id_font`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';


$query[] = '
        INSERT INTO `'._DB_PREFIX_.'innovativemenu_font_family`
                (`name`)
        VALUES ("Arial, Verdana, Helvetica, sans-serif"),
                ("Times New Roman, Times, serif"),
                ("Arial Black, Gadget, sans-serif"),
                ("Bookman Old Style, serif"),
                ("Comic Sans MS, cursive"),
                ("Courier, monospace"),
                ("Courier New, Courier, monospace"),
                ("Garamond, serif, Georgia, serif"),
                ("Impact, Charcoal, sans-serif"),
                ("Lucida Console, Monaco, monospace"),
                ("Lucida Sans Unicode"),
                ("Lucida Grande, sans-serif"),
                ("MS Sans Serif, Geneva, sans-serif"),
                ("MS Serif, New York, sans-serif"),
                ("Palatino Linotype, Book Antiqua, Palatino, serif"),
                ("Tahoma, Geneva, sans-serif"),
                ("Times New Roman, Times, serif"),
                ("Verdana, Geneva, sans-serif")
';

$query[] = '
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'innovativemenu_context`(
        `id_menu_context` INTEGER NOT NULL AUTO_INCREMENT,
        `type_context` TINYINT(2),
        `id_element` INTEGER NOT NULL,
PRIMARY KEY(`id_menu_context`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';