<?php /* Smarty version Smarty-3.1.14, created on 2014-04-11 17:56:31
         compiled from "/var/www/modules/csslider/csslider.tpl" */ ?>
<?php /*%%SmartyHeaderCode:4231873055348731f76e853-12517368%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0dbc0a7c885a9c1ccd0c7433c53b36bd6f6e1201' => 
    array (
      0 => '/var/www/modules/csslider/csslider.tpl',
      1 => 1391179042,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '4231873055348731f76e853-12517368',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sliders' => 0,
    'option' => 0,
    'slider' => 0,
    'path' => 0,
    'src' => 0,
    'caption' => 0,
    'cookie' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5348731f987b87_24389325',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5348731f987b87_24389325')) {function content_5348731f987b87_24389325($_smarty_tpl) {?><!-- CS Slider module -->
<?php if (isset($_smarty_tpl->tpl_vars['sliders']->value)&&count($_smarty_tpl->tpl_vars['sliders']->value)>0){?>
<script type="text/javascript">
				var api;
			jQuery(window).ready(function() {
			api =  jQuery(".<?php if ($_smarty_tpl->tpl_vars['option']->value->fullwidth=='on'){?>fullwidthbanner<?php }else{ ?>banner<?php }?>").revolution(
				{
					delay:<?php echo $_smarty_tpl->tpl_vars['option']->value->delay;?>
,
					startheight:<?php echo $_smarty_tpl->tpl_vars['option']->value->startheight;?>
,
					startwidth:<?php echo $_smarty_tpl->tpl_vars['option']->value->startwidth;?>
,

					hideThumbs:<?php echo $_smarty_tpl->tpl_vars['option']->value->timehidethumbnail;?>
,

					thumbWidth:<?php echo $_smarty_tpl->tpl_vars['option']->value->thumbnailwidth;?>
,		// Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
					thumbHeight:<?php echo $_smarty_tpl->tpl_vars['option']->value->thumbnailheight;?>
,
					thumbAmount:<?php echo $_smarty_tpl->tpl_vars['option']->value->thumbamount;?>
,

					navigationType:"<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationtype;?>
",		// bullet, thumb, none - trong
					navigationArrows:"<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationarrow;?>
",	// nexttobullets, solo (old name verticalcentered), none - mui ten

					navigationStyle:"<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationstyle;?>
",				//round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom - hinh cua icon tron
					navigationHAlign:"<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationhalign;?>
",				// Vertical Align top,center,bottom
					navigationVAlign:"<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationvalign;?>
",					// Horizontal Align left,center,right
					navigationHOffset:<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationhoffset;?>
,
					navigationVOffset:<?php echo $_smarty_tpl->tpl_vars['option']->value->navigationvoffset;?>
,

					soloArrowLeftHalign:"<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowlefthalign;?>
",
					soloArrowLeftValign:"<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowleftvalign;?>
",
					soloArrowLeftHOffset:<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowlefthoffset;?>
,
					soloArrowLeftVOffset:<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowleftvoffset;?>
,

					soloArrowRightHalign:"<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowrighthalign;?>
",
					soloArrowRightValign:"<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowrightvalign;?>
",
					soloArrowRightHOffset:<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowrighthoffset;?>
,
					soloArrowRightVOffset:<?php echo $_smarty_tpl->tpl_vars['option']->value->soloarrowrightvoffset;?>
,

					touchenabled:"<?php echo $_smarty_tpl->tpl_vars['option']->value->touchenabled;?>
",	// Enable Swipe Function : on/off
					onHoverStop:"<?php echo $_smarty_tpl->tpl_vars['option']->value->onhoverstop;?>
",	// Stop Banner Timet at Hover on Slide on/off

					stopAtSlide:<?php echo $_smarty_tpl->tpl_vars['option']->value->stopatslide;?>
,
					stopAfterLoops:<?php echo $_smarty_tpl->tpl_vars['option']->value->stopafterloops;?>
,
					
					hideCaptionAtLimit:<?php echo $_smarty_tpl->tpl_vars['option']->value->hidecapptionatlimit;?>
,					// It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
					hideAllCaptionAtLilmit:<?php echo $_smarty_tpl->tpl_vars['option']->value->hideallcapptionatlimit;?>
,				// Hide all The Captions if Width of Browser is less then this value
					hideSliderAtLimit:<?php echo $_smarty_tpl->tpl_vars['option']->value->hideslideratlimit;?>
,					// Hide the whole slider, and stop also functions if Width of Browser is less than this value

					
					shadow:<?php echo $_smarty_tpl->tpl_vars['option']->value->shadow;?>
,								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows  (No Shadow in Fullwidth Version !)
					fullWidth:"off"							// Turns On or Off the Fullwidth Image Centering in FullWidth Modus
						});
					});
			</script>
<div class="cs_revolution"> 
	<div class="<?php if ($_smarty_tpl->tpl_vars['option']->value->fullwidth=='on'){?>fullwidthbanner-container<?php }else{ ?>bannercontainer<?php }?> responsive">
		<div class="<?php if ($_smarty_tpl->tpl_vars['option']->value->fullwidth=='on'){?>fullwidthbanner<?php }else{ ?>banner<?php }?>">
		
			<ul>
				<?php  $_smarty_tpl->tpl_vars['slider'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['slider']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sliders']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['slider']->key => $_smarty_tpl->tpl_vars['slider']->value){
$_smarty_tpl->tpl_vars['slider']->_loop = true;
?>
					<?php if ($_smarty_tpl->tpl_vars['slider']->value['image']->type=="image"){?>
						<?php $_smarty_tpl->tpl_vars["src"] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['path']->value)."images/".((string)$_smarty_tpl->tpl_vars['slider']->value['image']->value), null, 0);?>
					<?php }else{ ?>
						<?php $_smarty_tpl->tpl_vars["src"] = new Smarty_variable(((string)$_smarty_tpl->tpl_vars['path']->value)."revolution/images/sliderbg/transparent.png", null, 0);?>
					<?php }?>
					<?php if ($_smarty_tpl->tpl_vars['slider']->value['image']->type=="color"){?>
					<style type="text/css">
						.thumb img {
							background-color:<?php echo $_smarty_tpl->tpl_vars['slider']->value['image']->value;?>
;
						}
					</style>
					<?php }?>
					<li data-transition="<?php echo $_smarty_tpl->tpl_vars['slider']->value['animation']->transitions;?>
" data-masterspeed="<?php echo $_smarty_tpl->tpl_vars['slider']->value['animation']->masterspeed;?>
" data-slotamount="<?php echo $_smarty_tpl->tpl_vars['slider']->value['animation']->slotamount;?>
" data-delay="<?php echo $_smarty_tpl->tpl_vars['slider']->value['animation']->delay;?>
" <?php if ($_smarty_tpl->tpl_vars['slider']->value['image']->type=="image"){?> data-thumb="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
images/thumbs/<?php echo $_smarty_tpl->tpl_vars['slider']->value['image']->value;?>
"<?php }?> >
					<?php if ($_smarty_tpl->tpl_vars['slider']->value['animation']->enablelink==1){?> <a class="cs_slidelink" href="<?php echo $_smarty_tpl->tpl_vars['slider']->value['url'];?>
" target="<?php echo $_smarty_tpl->tpl_vars['slider']->value['animation']->target;?>
"></a><?php }?> 
					<img src="<?php echo $_smarty_tpl->tpl_vars['src']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['slider']->value['image']->type=="color"){?>style="background-color:<?php echo $_smarty_tpl->tpl_vars['slider']->value['image']->value;?>
"<?php }?>/>
					<?php if (isset($_smarty_tpl->tpl_vars['slider']->value['caption'])){?>
						<?php  $_smarty_tpl->tpl_vars['caption'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['caption']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['slider']->value['caption']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['caption']->key => $_smarty_tpl->tpl_vars['caption']->value){
$_smarty_tpl->tpl_vars['caption']->_loop = true;
?>
							<div class="caption <?php echo $_smarty_tpl->tpl_vars['caption']->value->class;?>
 <?php echo $_smarty_tpl->tpl_vars['caption']->value->incomanimation;?>
 <?php echo $_smarty_tpl->tpl_vars['caption']->value->outgoanimation;?>
<?php if ($_smarty_tpl->tpl_vars['slider']->value['animation']->enablefullvideo==1&&$_smarty_tpl->tpl_vars['caption']->value->type_caption=='videocaption'){?> fullscreenvideo<?php }?>"
							data-x="<?php echo $_smarty_tpl->tpl_vars['caption']->value->datax;?>
" data-y="<?php echo $_smarty_tpl->tpl_vars['caption']->value->datay;?>
" <?php if (isset($_smarty_tpl->tpl_vars['caption']->value->hideunderwidth)){?> data-captionhidden="on"<?php }?> data-easing="<?php echo $_smarty_tpl->tpl_vars['caption']->value->easing;?>
" data-endeasing="<?php echo $_smarty_tpl->tpl_vars['caption']->value->endeasing;?>
" data-speed="<?php echo $_smarty_tpl->tpl_vars['caption']->value->dataspeed;?>
" data-start="<?php echo $_smarty_tpl->tpl_vars['caption']->value->datastart;?>
">
							
								<?php if ($_smarty_tpl->tpl_vars['caption']->value->type_caption=="textcaption"){?>
									<?php echo $_smarty_tpl->tpl_vars['caption']->value->text->{(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang};?>

								<?php }elseif($_smarty_tpl->tpl_vars['caption']->value->type_caption=="imagecaption"){?>
									<img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
images/captions/<?php echo $_smarty_tpl->tpl_vars['caption']->value->{'image_'.(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang};?>
" alt=""/>
								<?php }else{ ?>
									<?php if ($_smarty_tpl->tpl_vars['caption']->value->type_video->{(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang}==1){?>
										<iframe src="http://www.youtube.com/embed/<?php echo $_smarty_tpl->tpl_vars['caption']->value->type_id->{(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang};?>
?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0"></iframe>
									<?php }else{ ?>
										<iframe src="http://player.vimeo.com/video/<?php echo $_smarty_tpl->tpl_vars['caption']->value->type_id->{(int)$_smarty_tpl->tpl_vars['cookie']->value->id_lang};?>
?title=0&amp;byline=0&amp;portrait=0"></iframe>
									<?php }?>
								<?php }?>
								
							</div>
						<?php } ?>
					<?php }?>
					</li>
				<?php } ?>
			</ul>
			<?php if ($_smarty_tpl->tpl_vars['option']->value->timerline=="true"){?>
			<div class="tp-bannertimer<?php if ($_smarty_tpl->tpl_vars['option']->value->timerlineposition=='bottom'){?> tp-bottom<?php }?>"></div> 
			<?php }?>
		</div>
	</div>
</div>
<?php }?>
<!-- /CS Slider module --><?php }} ?>