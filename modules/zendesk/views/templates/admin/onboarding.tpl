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

    {if $dev_part eq 'onboarding' || $dev_part eq 'existing'}
      <article class="onboarding reg-refresh">
        <div class="hero">
          <h1>Bring your customers closer</h1>
          <!--
          <h1 style="font-family:DNLTUL">DNLTUL Bring your customers closer</h1>
          <h1 style="font-family:DNLTL">DNLTL Bring your customers closer</h1>
          <h1 style="font-family:DNLTR">DNLTR Bring your customers closer</h1>
          <h1 style="font-family:DNLTM">DNLTM Bring your customers closer</h1>
          <h1 style="font-family:DNLTB">DNLTB Bring your customers closer</h1>
          <h1 style="font-family:DNLTLI">DNLTLI Bring your customers closer</h1>
          <h1 style="font-family:DNLTRI">DNLTRI Bring your customers closer</h1>
          <h1 style="font-family:DNRL">DNRL Bring your customers closer</h1>
          <h1 style="font-family:DNRR">DNRR Bring your customers closer</h1>
          <h1 style="font-family:DNRM">DNRM Bring your customers closer</h1>
          <h1 style="font-family:DNRB">DNRB Bring your customers closer</h1>
          -->

          <h2>Zendesk makes software for better customer support. Over 70,000 companies worldwide use Zendesk to increase satisfaction and improve relationships with their customers.</h2>

          <div class="wistia_embed wistia_async_u7pbtggdg2 popover=true popoverContent=html zendesk-video-thumb" style="cursor: pointer !important"></div>

          <div id="dev_part-hero-buttons" class="dev_part {if $dev_part eq 'existing'}hide{/if}">
            <p id="hero-buttons">
              <a class="zendesk-btn zendesk-btn-gray" href="{$current|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;configure=zendesk&amp;part=existing">I have an account</a>
              <span>or</span> 
              <a class="zendesk-btn zendesk-btn-orange" href="{$current|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;configure=zendesk&amp;part=trial">Try it for free</a>
            </p>
          </div>

          <div id="dev_part-existing" class="dev_part {if $dev_part != 'existing'}hide{/if}">
            <h2>Where is your Zendesk located?</h2>

            <div class="step-wrap">
              <div class="step">
                <form id="submitSubDomainExist" method="post" class="reg" action="{$current|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;configure=zendesk&amp;page=settings">
                  <input type="hidden" name="submitSubDomainExist" value="1" />
                  <ul>
                    <li class="domain">
                      <input type="text" placeholder="subdomain" name="subdomain" id="subdomain" class="required" value="{$domain_suggestion|escape:'htmlall':'UTF-8'}">
                      <h4 class="domain-box">.zendesk.com</h4>
                      <div class="shadow">.zendesk.com</div>
                      <label class="error url"><span></span>Enter only letters and numbers</label>
                      <label class="suggested"><span class="info"></span>Checking domain availability...</label>
                    </li>

                    <li class="full create">
                      <div class="loading-indicator"></div>
                      <a id="btn-submitSubDomainExist" class="zendesk-btn zendesk-btn-orange" href="#">Next</a>
                    </li>
                  </ul>
                </form>
              </div>
            </div>
          </div>
        </div>


        <section id="features-faqs">
          <ul class="switcher nav">
            <li id="switcher_nav-1" class="active">Features</li>
            <li id="switcher_nav-2"><a href="#">FAQs</a></li>
          </ul>

          <div class="switcher content">
            <div id="switcher_features" class="row switcher_content">
              <div class="col-md-6">
                <p>This integration with Prestashop allows you to support your customers via email, phone, chat, web form, Twitter, Facebook, or self-service portal. The result is customer relationships that are more meaningful, personal, and productive&mdash;all at a lower cost.</p>
              </div>
              <div class="col-md-6">
                <p>A better experience turns customers into promotors. This means a bigger audience for your message, and ultimately, more customers for your company.</p>
              </div>
            </div>

            <div id="switcher_faqs" class="row switcher_content" style="display:none;">
              <div class="col-md-6">
                <p>
                  <strong>How does the free trial work?</strong><br>
                  When you sign up for a trial, you'll have access to all features in the <a href="https://www.zendesk.com/product/pricing/#compare" target="_blank">Professional Plan</a>. If you want to trial a specific plan, just <a href="https://www.zendesk.com/support/contact/" target="_blank">contact us</a>. At any point during the trial you can choose a plan and pay by credit card from within your account.
                </p>

                <p>
                  <strong>Will you ask for my credit card number?</strong><br>
                  Nope. We will never ask for your credit card information during the trial period. It really is free.
                </p>
              </div>
              <div class="col-md-6">
                <p>
                  <strong>What happens at the end of my trial?</strong><br>
                  At the end of a Zendesk trial, your data and setup remains intact. You can login and select a plan to purchase. Plans are billed monthly or annually. You can start/stop or make changes to your plan at any time.
                </p>

                <p>
                  <strong>How much does Zendesk cost?</strong><br>
                  Zendesk has several pricing plans available depending on needs. <a href="https://www.zendesk.com/product/pricing/" target="_blank">Learn more</a> about our pricing options.
                </p>
              </div>
            </div>
          </div>
        </section>
      </article>
    {/if}

    {if $dev_part eq 'trial'}
      <article class="trial reg-refresh">
        <a href="{$current|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;configure=zendesk&amp;part=onboarding" class="back">Back</a>

        <h1>Let's get started</h1>


        <div class="step-wrap">
          <div class="step">
            <form method="post" class="reg" id="trial-form">
              <input type="hidden" name="btnSubmitOnBoarding" value="1" />
              <ul>
                <li class="domain">
                  <label class="side-label" for="subdomain">Subdomain</label>
                  <input type="text" placeholder="subdomain" name="subdomain" id="subdomain" class="required" value="{$domain_suggestion|escape:'htmlall':'UTF-8'}">
                  <h4 class="domain-box">.zendesk.com</h4>
                  <div class="shadow">.zendesk.com</div>
                  <label class="error url"><span></span>Enter only letters and numbers</label>
                  <label class="suggested"><span class="info"></span>Checking domain availability...</label>
                  <!-- <span class="check"></span> -->
                  <span class="domain-ping pulse"></span>
                </li>

                <li>
                  <label class="side-label" for="company">Company name</label>
                  <input type="text" placeholder="" name="company" id="company" class="required" value="{$shop_name|escape:'htmlall':'UTF-8'}">
                  <label class="error"><span></span>Enter your company name</label>
                  <span class="check"></span>
                </li>

                <li>
                  <label class="side-label" for="name">Full name</label>
                  <input type="text" placeholder="" name="name" id="name" class="required">
                  <label class="error"><span></span>Enter your full name</label>
                </li>

                <li>
                  <label class="side-label" for="owner_email">Owner email</label>
                  <input type="text" placeholder="" name="owner_email" id="owner_email" class="required" value="{$owner_email|escape:'htmlall':'UTF-8'}">
                  <label class="error"><span></span>Enter your owner email</label>
                </li>

                <li>
                  <label class="side-label" for="shop_phone">Phone number</label>
                  <input type="text" placeholder="" name="shop_phone" id="shop_phone" class="required" value="{$shop_phone|escape:'htmlall':'UTF-8'}">
                  <label class="error"><span></span>Enter your shop phone</label>
                </li>

                <li id="num-seats-row">
                  <label class="side-label" for="expected_num_seats]">Size of service desk</label>
                  <span class="select" id="select-agents">
                    -<span></span>
                  </span>
                  <select type="select" name="expected_num_seats" id="expected_num_seats" class="agents step4">
                    <option value="-" selected="">-</option>
                    <option value="1-14">1-14 seats</option>
                    <option value="15-99">15-99 seats</option>
                    <option value="100-999">100-999 seats</option>
                    <option value="1000+">1000+ seats</option>
                  </select>
                  <label class="error"><span></span>Select # of seats</label>
                  <span class="check"></span>
                </li>

                <li class="short">
                  <div class="languages">
                    <span class="select language" id="selected-lang">Your Zendesk will be hosted in <a>English<span></span></a></span>
                    <!--en-->                
                    <select class="language" name="language" id="language">
                      <option value="de" id="de">Deutsch</option>
                      <option value="en" id="en" selected="selected">English</option>
                      <option value="en-gb" id="en-gb">English (UK)</option>
                      <option value="es" id="es">Español</option>
                      <option value="fr" id="fr">Français</option>
                      <option value="it" id="it">Italiano</option>
                      <option value="nl" id="nl">Nederlands</option>
                      <option value="pl" id="pl">Polski</option>
                      <option value="pt-br" id="pt-br">Português (Brasil)</option>
                      <option value="ru" id="ru">Русский</option>
                      <option value="zh-cn" id="zh-cn">中文(简体)</option>
                      <option value="zh-tw" id="zh-tw">中文(繁體)</option>
                      <option value="ja" id="ja">日本語</option>
                      <option value="ko" id="ko">한국어</option>
                    </select>
                  </div>
                </li>

                <li class="full create">
                  <div class="loading-indicator"></div>
                  <a id="btnCreateTrial" class="zendesk-btn-next zendesk-btn zendesk-btn-orange" href="{$current|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;configure=zendesk&amp;page=settings">Next</a>
                </li>
              </ul>
            </form>
          </div>
        </div>
      </article>
    {/if}

  </div>
</div>