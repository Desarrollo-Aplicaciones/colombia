{*
* 2009-2016 Presta Module
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
*  @author    Presta-Module.com <support@presta-module.com>
*  @copyright 2009-2016 Presta Module SAS
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}
<div id="module-zendesk-configuration">
  <!--[if lt IE 9]>
    <script src="https://d26a57ydsghvgx.cloudfront.net/www/public/assets/js/css3-mediaqueries.js"></script>
  <![endif]-->

  {*<link rel="stylesheet" type="text/css" media="all" href="../modules/zendesk/views/css/cloudfront.css?v=3" />*}
  <link rel="stylesheet" type="text/css" media="all" href="../modules/zendesk/views/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" media="all" href="../modules/zendesk/views/css/fonts.css" />
  <link rel="stylesheet" type="text/css" media="all" href="../modules/zendesk/views/css/cloudfront.css" />
  <link rel="stylesheet" type="text/css" media="all" href="../modules/zendesk/views/css/screen.css?v=3" />

  <script charset="ISO-8859-1" src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
  <script charset="ISO-8859-1" src="https://fast.wistia.net/static/popover-v1.js"></script>

  <script src="https://cdn.optimizely.com/js/112699136.js"></script>
  <script src="../modules/zendesk/views/js/script.js"></script>

  <div id="dev_page-index" class="dev_page">
	<article class="reg-refresh">
    <h2>Account settings</h2>

    <form action="{$current|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;configure=zendesk" method="post" class="reg">
      <input type="hidden" name="submitConfig" value="1" />
      <div class="row">
        <div class="col-md-6">        
            <ul>
              <li class="domain">
                <label class="side-label" for="subdomain">Subdomain</label>
                <input type="text" placeholder="subdomain" name="subdomain" id="subdomain" class="required" value="{$zendesk_subdomain|escape:'htmlall':'UTF-8'}">
                <h4 class="domain-box">.zendesk.com</h4>
                <div class="shadow">.zendesk.com</div>
                <label class="error url"><span></span>Enter only letters and numbers</label>
                <label class="suggested"><span class="info"></span>Checking domain availability...</label>
                <!-- <span class="check"></span> -->
                <span class="domain-ping pulse"></span>
              </li>

              <li>
                <label class="side-label" for="email">Account email</label>
                <input type="text" placeholder="" name="email" id="email" class="required" value="{$zendesk_email|escape:'htmlall':'UTF-8'}">
              </li>

              <li>
                <label class="side-label" for="api_key">API key</label>
                <input type="text" placeholder="" name="api_key" id="api_key" class="required" value="{$zendesk_api_key|escape:'htmlall':'UTF-8'}">

                <div class="description">
                  To generate a new API token, go to <a href="https://{$zendesk_subdomain|escape:'htmlall':'UTF-8'}.zendesk.com/agent/admin/api" target="_blank">Zendesk > Admin > Channels > API</a>
                  <ul>
                    <li>In the Settings tab, select "add new token"</li>
                    <li>Name the new token "Prestashop". Click "Create"</li>
                    <li>Copy and paste your new API token in the field above. Need help? <a href="https://www.zendesk.com/support/contact/" target="_blank">Click here</a></li>
                  </ul>
                </div>

              </li>
            </ul>
          
        </div>
      </div>


      <section id="settings_embed" class="settings">
        <div class="demo">
          <video width="414" height="462" controls autoplay loop poster="../modules/zendesk/views/img/blank_poster.png">
            <source src="../modules/zendesk/views/img/Zendesk-widget2.mp4" type="video/mp4">
            I'm sorry; your browser doesn't support HTML5 video in MP4 with H.264.
            <!-- You can embed a Flash player here, to play your mp4 video in older browsers -->
          </video>
        </div>

        <h2>Embed Zendesk widget in Prestashop</h2>

        <p>The Zendesk widget seamlessly integrates Zendesk functionality into your Prestashop storefront. Using the Zendesk widget, you can reach out to your customers and offer support, provide information, or start a conversation. <a href="https://www.zendesk.com/embeddables/" target="_blank">See it in action</a></p>

        <div class="switch-container pull-right">
          <div class="switch">
            <label class="switch-label" for="embed_enabled">
            <input name="embed-toggle" type="hidden" value="0"><input {if $zendesk_widget}checked="checked"{/if} class="switch-checkbox config_set_configs_ticket_submission_enabled" id="embed_enabled" name="embed-toggle" type="checkbox" value="1">
            <span class="switch-content">
            <span class="switch-bg"></span>
            <span class="switch-toggle"></span>
            </span>
            </label>
          </div>
        </div>

        <p>
          <strong>Enable Zendesk web widget on your shop</strong><br>
          You can customize your widget in your <a href="#">Zendesk Web Widget settings</a>
        </p>
      </section>


      <section id="settings_enable" class="settings">
        <div class="demo">
          <img src="../modules/zendesk/views/img/prestashop-app.png" width="318" height="318" alt="">
        </div>

        <h2>Enable Prestashop app in Zendesk</h2>

        <p>The Zendesk for Prestashop app unites your business by displaying critical Prestashop data inside your Zendesk, next to your ticket information. This app queries your Prestashop store to find customer details and recent orders. <a href="https://www.zendesk.com/apps/prestashop/" target="_blank">Learn more</a></p>

        <div class="switch-container pull-right">
          <div class="switch">
            <label class="switch-label" for="settings_enabled">
            <input name="settings-toggle" type="hidden" value="0"><input {if $zendesk_app}checked="checked"{/if} class="switch-checkbox config_set_configs_ticket_submission_enabled" id="settings_enabled" name="settings-toggle" type="checkbox" value="1">
            <span class="switch-content">
            <span class="switch-bg"></span>
            <span class="switch-toggle"></span>
            </span>
            </label>
          </div>
        </div>

        <p>
          <strong>Enable Prestashop app in Zendesk</strong><br>
          You can edit your app settings in <a href="https://{$zendesk_subdomain|escape:'htmlall':'UTF-8'}.zendesk.com/agent/admin/apps/manage" target="_blank">Zendesk App management</a>
        </p>
      </section>

      <div class="submit-row">
        <button type="submit" class="zendesk-btn">Save</button>
      </div>

    </form>
  </article>
  </div>
</div>