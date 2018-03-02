<?php
/**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class wim_ganalytics extends Module
{
    protected $config_form = false;
    protected $base_url = "https://www.google-analytics.com/collect";
    protected $base_ssl_url = "https://ssl.google-analytics.com/collect";
    protected $debug_url = "https://www.google-analytics.com/debug/collect";
    protected $debug_ssl_url = "https://ssl.google-analytics.com/debug/collect";


public function __construct()
    {
        $this->name = 'wim_ganalytics';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Webimpacto';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Google analytics by Webimpacto');
        $this->description = $this->l('Send data to google analytics without using pixels');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('actionDispatcher') &&
                $this->registerHook('actionValidateOrder') &&
                $this->registerHook('footer') &&
                $this->registerHook('header') &&
                $this->registerHook('displaymobileheader');
    }

    public function uninstall()
    {
        

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitWim_ganalyticsModule')) == true) {
            $this->postProcess();
        }
        
        $style15 = '<style>
                label[for="active_on"],label[for="active_off"]{
                    float: none
                }
                </style>';

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        
        if (PS_VERSION < 1.6) {
            $output .= $style15;
        }

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitWim_ganalyticsModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($this->getConfigForm());
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('General Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(                   
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Debug mode'),
                        'name' => 'WIM_GANALYTICS_DEBUG_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use the module in debug mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('SSL connection'),
                        'name' => 'WIM_GANALYTICS_SSL_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use a secure connection'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Use Javascript'),
                        'name' => 'WIM_GANALYTICS_USE_JAVASCRIPT',
                        'is_bool' => true,
                        'desc' => $this->l('Use javascript or http request'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Custom UUID'),
                        'name' => 'WIM_GANALYTICS_CUSTOM_UUID',
                        'is_bool' => true,
                        'desc' => $this->l('Use custom UUID for new users'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Use POST'),
                        'name' => 'WIM_GANALYTICS_REQUEST_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use POST or GET method to send the data'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => TRUE,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => FALSE,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Protocol´s version'),
                        'desc' => $this->l('Choose a version of protocol'),
                        'name' => 'WIM_GANALYTICS_VERSION_PROTOCOL',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('Version 1')
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name' 
                        )
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Preventing caching'),
                        'name' => 'WIM_GANALYTICS_PREVENT_CACHE',
                        'is_bool' => true,
                        'desc' => $this->l('Preventing caching of server or proxy'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => TRUE,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => FALSE,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )),
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('LOG Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Enable log'),
                        'name' => 'WIM_GANALYTICS_ENABLE_LOG',
                        'is_bool' => true,
                        'desc' => $this->l('Enable the log in the data base'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Clear old data'),
                        'desc' => $this->l('Prevents a large log size'),
                        'name' => 'WIM_GANALYTICS_DELETE_LOG',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 0,
                                    'name' => $this->l('Never')
                                ),
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('A day')
                                ),
                                array(
                                    'id_option' => 2,
                                    'name' => $this->l('A week')
                                ),
                                array(
                                    'id_option' => 3,
                                    'name' => $this->l('A month')
                                ),
                                array(
                                    'id_option' => 4,
                                    'name' => $this->l('A year')
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name' 
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )),
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('General data'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tracking ID'),
                        'name' => 'WIM_GANALYTICS_TRACKING_ID',
                        'desc' => $this->l('Tracking ID or Web Property ID'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Tracker name'),
                        'name' => 'WIM_GANALYTICS_TRACKER_NAME',
                        'desc' => $this->l('Indicates the tracker name'),
                    ),
                   array(
                        'type' => 'select',
                        'label' => $this->l('Validity of the user ID'),
                        'desc' => $this->l('Time validity of the user ID'),
                        'name' => 'WIM_GANALYTICS_VALIDITY_USERID',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 0,
                                    'name' => $this->l('2 year (Recomended)')
                                ),
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('A year')
                                ),
                                array(
                                    'id_option' => 2,
                                    'name' => $this->l('A month')
                                ),
                                array(
                                    'id_option' => 3,
                                    'name' => $this->l('A week')
                                ),
                                array(
                                    'id_option' => 4,
                                    'name' => $this->l('A day')
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name' 
                        )
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('User IP'),
                        'name' => 'WIM_GANALYTICS_USER_IP',
                        'is_bool' => true,
                        'desc' => $this->l('Send the user IP'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('User agent'),
                        'name' => 'WIM_GANALYTICS_USER_AGENT',
                        'is_bool' => true,
                        'desc' => $this->l('Send the user agent'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('User lang'),
                        'name' => 'WIM_GANALYTICS_USER_LANG',
                        'is_bool' => true,
                        'desc' => $this->l('Send the user lang'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('User location'),
                        'desc' => $this->l('Send user location, the city mode requires a update csv file from Google'),
                        'name' => 'WIM_GANALYTICS_USER_LOCATION',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 0,
                                    'name' => $this->l('Disable')
                                ),
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('Country mode')
                                ),
                                array(
                                    'id_option' => 2,
                                    'name' => $this->l('City mode')
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name' 
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )),
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Traffic Sources'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Reference URL'),
                        'name' => 'WIM_GANALYTICS_REFERENCE_URL',
                        'is_bool' => true,
                        'desc' => $this->l('Send the reference URL'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )),
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Content Information'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Document URL'),
                        'desc' => $this->l('Send actual document URL'),
                        'name' => 'WIM_GANALYTICS_DOCUMENT_URL',
                        'required' => true,
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option' => 0,
                                    'name' => $this->l('Disable')
                                ),
                                array(
                                    'id_option' => 1,
                                    'name' => $this->l('Full URL')
                                ),
                                array(
                                    'id_option' => 2,
                                    'name' => $this->l('Host + Route')
                                ),
                            ),
                            'id' => 'id_option',
                            'name' => 'name' 
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )),
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Commerce setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Ecommerce improved'),
                        'name' => 'WIM_GANALYTICS_ECOMMERCE_IMPROVED',
                        'is_bool' => true,
                        'desc' => $this->l('Use fields for Ecommerce improved'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            )),
            array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Exception setting'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => (PS_VERSION >= 1.6) ? 'switch' : 'radio',
                        'label' => $this->l('Enable exceptions'),
                        'name' => 'WIM_GANALYTICS_ENABLE_EXCEPTION',
                        'is_bool' => true,
                        'desc' => $this->l('Enabled send the exception´s data'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ))
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {


            $WIM_GANALYTICS_DEBUG_MODE = Configuration::get('WIM_GANALYTICS_DEBUG_MODE');
            $WIM_GANALYTICS_CUSTOM_UUID = Configuration::get('WIM_GANALYTICS_CUSTOM_UUID');
            $WIM_GANALYTICS_USE_JAVASCRIPT = Configuration::get('WIM_GANALYTICS_USE_JAVASCRIPT');
            $WIM_GANALYTICS_SSL_MODE = Configuration::get('WIM_GANALYTICS_SSL_MODE');
            $WIM_GANALYTICS_REQUEST_MODE = Configuration::get('WIM_GANALYTICS_REQUEST_MODE');
            $WIM_GANALYTICS_VERSION_PROTOCOL = Configuration::get('WIM_GANALYTICS_VERSION_PROTOCOL');
            $WIM_GANALYTICS_PREVENT_CACHE = Configuration::get('WIM_GANALYTICS_PREVENT_CACHE');
            $WIM_GANALYTICS_ENABLE_LOG = Configuration::get('WIM_GANALYTICS_ENABLE_LOG');
            $WIM_GANALYTICS_DELETE_LOG = Configuration::get('WIM_GANALYTICS_DELETE_LOG');
            $WIM_GANALYTICS_TRACKING_ID = Configuration::get('WIM_GANALYTICS_TRACKING_ID');
            $WIM_GANALYTICS_TRACKER_NAME = Configuration::get('WIM_GANALYTICS_TRACKER_NAME');
            $WIM_GANALYTICS_VALIDITY_USERID = Configuration::get('WIM_GANALYTICS_VALIDITY_USERID');
            $WIM_GANALYTICS_USER_IP = Configuration::get('WIM_GANALYTICS_USER_IP');
            $WIM_GANALYTICS_USER_AGENT = Configuration::get('WIM_GANALYTICS_USER_AGENT');
            $WIM_GANALYTICS_USER_LANG = Configuration::get('WIM_GANALYTICS_USER_LANG');
            $WIM_GANALYTICS_USER_LOCATION = Configuration::get('WIM_GANALYTICS_USER_LOCATION');
            $WIM_GANALYTICS_REFERENCE_URL = Configuration::get('WIM_GANALYTICS_REFERENCE_URL');
            $WIM_GANALYTICS_DOCUMENT_URL = Configuration::get('WIM_GANALYTICS_DOCUMENT_URL');
            $WIM_GANALYTICS_ECOMMERCE_IMPROVED = Configuration::get('WIM_GANALYTICS_ECOMMERCE_IMPROVED');
            $WIM_GANALYTICS_ENABLE_EXCEPTION = Configuration::get('WIM_GANALYTICS_ENABLE_EXCEPTION');
        

        return array(
            'WIM_GANALYTICS_DEBUG_MODE' => (!is_null($WIM_GANALYTICS_DEBUG_MODE) ? $WIM_GANALYTICS_DEBUG_MODE : false),
            'WIM_GANALYTICS_CUSTOM_UUID' => (!is_null($WIM_GANALYTICS_CUSTOM_UUID) ? $WIM_GANALYTICS_CUSTOM_UUID : false),
            'WIM_GANALYTICS_USE_JAVASCRIPT' => (!is_null($WIM_GANALYTICS_USE_JAVASCRIPT) ? $WIM_GANALYTICS_USE_JAVASCRIPT: true),
            'WIM_GANALYTICS_SSL_MODE' => (!is_null($WIM_GANALYTICS_SSL_MODE) ? $WIM_GANALYTICS_SSL_MODE : false),
            'WIM_GANALYTICS_REQUEST_MODE' => (!is_null($WIM_GANALYTICS_REQUEST_MODE) ? $WIM_GANALYTICS_REQUEST_MODE : true),
            'WIM_GANALYTICS_VERSION_PROTOCOL' => (!is_null($WIM_GANALYTICS_VERSION_PROTOCOL) ? $WIM_GANALYTICS_VERSION_PROTOCOL : 1),
            'WIM_GANALYTICS_PREVENT_CACHE' => (!is_null($WIM_GANALYTICS_PREVENT_CACHE) ? $WIM_GANALYTICS_PREVENT_CACHE : true),
            'WIM_GANALYTICS_ENABLE_LOG' => (!is_null($WIM_GANALYTICS_ENABLE_LOG) ? $WIM_GANALYTICS_ENABLE_LOG : true),
            'WIM_GANALYTICS_DELETE_LOG' => (!is_null($WIM_GANALYTICS_DELETE_LOG) ? $WIM_GANALYTICS_DELETE_LOG : 3),
            'WIM_GANALYTICS_TRACKING_ID' => (!is_null($WIM_GANALYTICS_TRACKING_ID) ? $WIM_GANALYTICS_TRACKING_ID : null),
            'WIM_GANALYTICS_TRACKER_NAME' => (!is_null($WIM_GANALYTICS_TRACKER_NAME) ? $WIM_GANALYTICS_TRACKER_NAME : 'ga'),
            'WIM_GANALYTICS_VALIDITY_USERID' => (!is_null($WIM_GANALYTICS_VALIDITY_USERID) ? $WIM_GANALYTICS_VALIDITY_USERID : 0),
            'WIM_GANALYTICS_USER_IP' => (!is_null($WIM_GANALYTICS_USER_IP) ? $WIM_GANALYTICS_USER_IP : true),
            'WIM_GANALYTICS_USER_AGENT' => (!is_null($WIM_GANALYTICS_USER_AGENT) ? $WIM_GANALYTICS_USER_AGENT : true),
            'WIM_GANALYTICS_USER_LANG' => (!is_null($WIM_GANALYTICS_USER_LANG) ? $WIM_GANALYTICS_USER_LANG : true),
            'WIM_GANALYTICS_USER_LOCATION' => (!is_null($WIM_GANALYTICS_USER_LOCATION) ? $WIM_GANALYTICS_USER_LOCATION : 2),
            'WIM_GANALYTICS_REFERENCE_URL' => (!is_null($WIM_GANALYTICS_REFERENCE_URL) ? $WIM_GANALYTICS_REFERENCE_URL : true),
            'WIM_GANALYTICS_DOCUMENT_URL' => (!is_null($WIM_GANALYTICS_DOCUMENT_URL) ? $WIM_GANALYTICS_DOCUMENT_URL : 1),
            'WIM_GANALYTICS_ECOMMERCE_IMPROVED' => (!is_null($WIM_GANALYTICS_ECOMMERCE_IMPROVED) ? $WIM_GANALYTICS_ECOMMERCE_IMPROVED : true),
            'WIM_GANALYTICS_ENABLE_EXCEPTION' => (!is_null($WIM_GANALYTICS_ENABLE_EXCEPTION) ? $WIM_GANALYTICS_ENABLE_EXCEPTION : true),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookHeader($param) {
        
        $track_id = Configuration::get('WIM_GANALYTICS_TRACKING_ID');

        if(empty($track_id)) return;
        
            $clientID = $this->getUUID($this->context->customer->id_guest);
            if($clientID)
                $uuid = str_replace('"', "'", Tools::jsonEncode($clientID));
            else
                $uuid = '\'auto\'';
            $tracker_name = Configuration::get('WIM_GANALYTICS_TRACKER_NAME');
            $tracker = (!is_null($tracker_name) ? $tracker_name : 'ga');
            $output = '<script type="text/javascript">
				(window.gaDevIds=window.gaDevIds||[]).push(\'d6YPbH\');
				(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\''.$tracker.'\');
				'.$tracker.'(\'create\', \''.strtoupper($track_id).'\', '.$uuid.', {\'allowLinker\': true });'
                    . $tracker.'(\'require\', \'linker\');'
                    . $tracker.'(\'linker:autoLink\', [\'redsys.es\',\'sis.redsys.es\',\'paypal.com\']);'
                    . '</script>';
            
        
        return $output;
			
    }
    
    function hookDisplayMobileHeader($params)
    {

            // for retrocompatibility
            return $this->hookHeader($params);
    }
    
    public function hookFooter($param) {
        
        $track_id = Configuration::get('WIM_GANALYTICS_TRACKING_ID');


        if(empty($track_id)) return;
        
            $uuid = false;
            $id_guest = $this->context->customer->id_guest;
            $uuid = $this->getUUID($id_guest);
            
            
            
            
            $output = '<script type="text/javascript">';
				
             $output .= $this->sendAnalytics($id_guest,$uuid);      

        
        return $output.'</script>';
			
    }

    function hookDisplayMobileFooter($params)
    {
            // for retrocompatibility
            return $this->hookFooter($params);
    }
    
    public function hookActionValidateOrder($params)
	{

        $id_guest = $params['cart']->id_guest;
        $uuid = $this->getUUID($id_guest);
        $this->sendAnalytics($id_guest, $uuid, true, $params);
        return;
    }
    
    public function hookActionDispatcher($params)
	{
        
        if (!isset(Context::getContext()->cookie->id_guest)){
                    Guest::setNewGuest(Context::getContext()->cookie);
        }

        $id_guest = null;

        if(isset($this->context->controller->php_self)){
            $id_guest = $this->context->customer->id_guest;
        }

        $uuid = $this->getUUID($id_guest);
        $v = Configuration::get('WIM_GANALYTICS_VERSION_PROTOCOL');
        $tid = strtoupper(Configuration::get('WIM_GANALYTICS_TRACKING_ID'));
        $cid = $uuid;

        $controller = null;
        if(isset($this->context->controller->php_self)){
            $controller = $this->context->controller->php_self;
        }
        $uid = $id_guest;
        
        if(empty($v) || empty($tid) || (empty($controller) ))
            return;
        
        $data= array();
        $userIP = $this->getRemoteAdderess($uid);
        $userAgent = $this->getUserAgent($uid);
        $userLocation = $this->getUserLocation($uid);
        $url = urlencode(Tools::getHttpHost(true).$_SERVER['REQUEST_URI']);
        $host = urlencode(Tools::getHttpHost());
        $uri = urlencode($_SERVER['REQUEST_URI']);
        $referrer = $this->getReferer();
        $wim_ecomerce_improved = Configuration::get('WIM_GANALYTICS_ECOMMERCE_IMPROVED');
        if(!$wim_ecomerce_improved || $controller != "cart") return;
        
        $id_product = Tools::getValue('id_product',false);
         if($id_product){
                        $nbProducts = 1;
                        $product = new Product($id_product,false,$this->context->language->id);
                        
                        
                        $product_id = 0;
		if (!empty($product->id))
			$product_id = $product->id;
		else if (!empty($product->id_product))
			$product_id = $product->id_product;
		
        $ipa = Tools::getValue('ipa');
		if (!empty($ipa))
			$product_id .= '-'. $ipa;
                
                $data['pr'.$nbProducts.'id'] = urlencode($product_id);
                $data['pr'.$nbProducts.'nm'] = urlencode($product->name);
                $data['pr'.$nbProducts.'ca'] = urlencode($product->category);

                $data['pr'.$nbProducts.'pr'] = $product->price;
                $data['pr'.$nbProducts.'qt'] = urlencode(abs(Tools::getValue('qty', 1)));
                        
                        
                        
                    }else{
                        return;
                    }

                    $add_get = Tools::getValue('add',false);
                    $delete_get = Tools::getValue('delete',false);
                    if($add_get){
                        
                        if($add_get=='down')
                            $data['pa'] = urlencode('remove');
                        else
                            $data['pa'] = urlencode('add');
                        
                    }elseif ($delete_get) {
                            $data['pa'] = urlencode('remove');
                            
                        }
                        
                   
        $data['v'] = $v;
                $data['tid'] = $tid;
                $data['cid'] = $cid;
                $data['uid'] = $uid;
                $data['t'] = urlencode('pageview');
                $data['dt'] = urlencode($this->context->smarty->getVariable('meta_title')->value);
            
        $document_url_mode = Configuration::get('WIM_GANALYTICS_DOCUMENT_URL');
        switch ($document_url_mode){
            case 0:
                break;
            case 1:
                $data['dl'] = $url;
                break;
            case 2:
                $data['dh'] = $host;
                $data['dp'] = $uri;
                break;
            default:
                $data['dl'] = $url;
        }
            
            if($userIP) $data['uip'] = $userIP;
            if($userAgent) $data['ua'] = $userAgent;
            if($userLocation) $data['geoid'] = $userLocation;
            if($referrer) $data['dr'] = $referrer;
            //ppp($data);
            
            $this->sendHTTPRequest ($data);
        
        return;
        
        
    }
    
    private function getUUID($id_guest){
        
        $uuid = false;
        if (empty($id_guest)) return $uuid;
        if (isset($_COOKIE['_ga'])) {
            list($version, $domainDepth, $cid1, $cid2) = split('[\.]', $_COOKIE["_ga"], 4);
            $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . ((!empty($cid2))?'.' . $cid2:''));
            $uuid = $contents['cid'];
            $luuid = DB::getInstance()->getValue('SELECT uuid from '._DB_PREFIX_.'wim_ganalytics_uuid WHERE id_guest='.$id_guest);
            if($uuid != $luuid){
                DB::getInstance()->delete('wim_ganalytics_uuid', 'id_guest ='.$id_guest);
                DB::getInstance()->insert('wim_ganalytics_uuid', array('id_guest' => $id_guest,'uuid' => $uuid,'ip' => Tools::getRemoteAddr(), 'agent' => $_SERVER['HTTP_USER_AGENT'],'date_add' => date("Y-m-d H:i:s")));
            }return $uuid;
        }
        

        
        $custom_uuid = Configuration::get('WIM_GANALYTICS_CUSTOM_UUID');
        if ($custom_uuid) {
            $uuid = DB::getInstance()->getValue('SELECT uuid from '._DB_PREFIX_.'wim_ganalytics_uuid WHERE id_guest='.$id_guest);
            if(!$uuid)
                $uuid = $this->generateUUIDV4($id_guest);
            DB::getInstance()->insert('wim_ganalytics_uuid', array('id_guest' => $id_guest,'uuid' => $uuid,'ip' => Tools::getRemoteAddr(), 'agent' => $_SERVER['HTTP_USER_AGENT'],'date_add' => date("Y-m-d H:i:s")));
        } else {
            $uuid = DB::getInstance()->getValue('SELECT uuid from '._DB_PREFIX_.'wim_ganalytics_uuid WHERE id_guest='.$id_guest);
        }
        
        

        if(!$uuid) return false;
        
        $result = $uuid;
        
        return $result;
               
    }
    
    private function generateUUIDV4($id_guest = 0){
        
        
        do{
        $md5 = md5(Tools::getRemoteAddr().'-'.$id_guest.'-'.round(microtime(true) * 1000));
        }while(strlen($md5)<32);
        
        $md5=strtolower($md5);
        
        $spc_char = array('8','9','a','b');
        $random = rand(0, 3);
        
        $UUID = substr($md5, 0, 8).'-'.substr($md5, 8, 4).'-4'.substr($md5, 13, 3).'-'.$spc_char[$random].substr($md5, 17, 3).'-'.substr($md5, 20, 12);

        return $UUID;
    }
    
    public function getRemoteAdderess($id_guest){
        
        $custom_ip = Configuration::get('WIM_GANALYTICS_USER_IP');
        if(!$custom_ip) return false;
        
        $ip = DB::getInstance()->getValue('SELECT ip from '._DB_PREFIX_.'wim_ganalytics_uuid WHERE id_guest='.$id_guest);
        $remote_addr = Tools::getRemoteAddr();
        if(!$ip && !empty($remote_addr))
                $ip = Tools::getRemoteAddr();
                
        return urlencode($ip);
    }
    
    public function getUserAgent($id_guest){
        
        $custom_agent = Configuration::get('WIM_GANALYTICS_USER_AGENT');
        if(!$custom_agent) return false;
        
        $agent = DB::getInstance()->getValue('SELECT agent from '._DB_PREFIX_.'wim_ganalytics_uuid WHERE id_guest='.$id_guest);
        
        if(!$agent && isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT']))
                $agent = $_SERVER['HTTP_USER_AGENT'];
                
        return urlencode($agent);
    }
    
    public function getUserLocation($id_guest){
        
        
        return false;
    }
    
    public function getReferer(){
        
        $referer_value = Configuration::get('WIM_GANALYTICS_REFERENCE_URL');
        if($referer_value && isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
            return urlencode(Configuration::get($_SERVER['HTTP_REFERER']));
        
		$http_host = Tools::getHttpHost();
        
        if(isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != $http_host)
            return urlencode(Configuration::get($_SERVER['HTTP_REFERER']));    
        
        return false;
    }

    public function sendHTTPRequest($fields){

        $debug_mode = Configuration::get('WIM_GANALYTICS_DEBUG_MODE');
        $ssl_mode = Configuration::get('WIM_GANALYTICS_SSL_MODE');
        if($debug_mode && $ssl_mode){
            $url = $this->debug_ssl_url;
        }elseif($debug_mode && !$ssl_mode){
            $url = $this->debug_url;
        }elseif(!$debug_mode && $ssl_mode){
            $url = $this->base_ssl_url;
        }else{
            $url = $this->base_url;
        }
        
        $prevent_cache= Configuration::get('WIM_GANALYTICS_PREVENT_CACHE');
        if($prevent_cache)
                $fields['z'] = urlencode(''.round(microtime(true) * 1000));
        
        $parametres = http_build_query($fields);
        
        $request_mode = Configuration::get('WIM_GANALYTICS_REQUEST_MODE');
        if(!$request_mode)
            $url .= '?'.$parametres;
        
        //d($parametres);
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    

    if($request_mode){        
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parametres);
        
    }
    
    $response = curl_exec($curl);
    
    
    $code = curl_getinfo($curl);
    //print_rr($headers);
    curl_close($curl);


    //return array($body, $headers);

    //fd(Tools::jsonDecode($response),'info');

    if($debug_mode)    
        fd(Tools::jsonDecode($response),'info');
        
    }
        
    public function printStadisticsCommands() {
        
    }

    public function sendAnalytics($id_guest,$uuid=false,$isTransaction=false,$params=null){
        
        $outputJS = "";
        $outputHTTP = array();
        
        if($isTransaction){
            $onlyJS = false;
            $id_guest = $params['cart']->id_guest;
            $uuid = $this->getUUID($id_guest);
        }else{
            $onlyJS = (!$uuid || Configuration::get('WIM_GANALYTICS_USE_JAVASCRIPT'));
        }
        
        $loaded_tracker_name = Configuration::get('WIM_GANALYTICS_TRACKER_NAME');
        $tracker = (!is_null($loaded_tracker_name) ? $loaded_tracker_name : 'ga');
        
        $v = Configuration::get('WIM_GANALYTICS_VERSION_PROTOCOL');
        $tid = strtoupper(Configuration::get('WIM_GANALYTICS_TRACKING_ID'));
        $cid = $uuid;
        $controller = $this->context->controller->php_self;
        $uid = $id_guest;
        $fixHTTP = false;
        
        
        
        if(empty($v) || empty($tid) || (empty($controller) && !$isTransaction))
            return;
        
        $data= array();
        $nbProducts = 1;
       
        
        //fix para el order validation
        
        $userIP = $this->getRemoteAdderess($uid);
        $userAgent = $this->getUserAgent($uid);
        $userLocation = $this->getUserLocation($uid);
        $url = urlencode(Tools::getHttpHost(true).$_SERVER['REQUEST_URI']);
        $host = urlencode(Tools::getHttpHost());
        $uri = urlencode($_SERVER['REQUEST_URI']);
        $referrer = $this->getReferer();
        
        
        $ecomerce_improved = Configuration::get('WIM_GANALYTICS_ECOMMERCE_IMPROVED');
        
        if(!$isTransaction){
        switch (strtolower($controller)){
            case 'index':
                if(!$ecomerce_improved) break;
                if($onlyJS) $outputJS .= $tracker."('require', 'ec');";
                // Home featured products
                $nbProducts = 0; 
		if (Module::isInstalled('homefeatured') && Module::isEnabled('homefeatured') && $ecomerce_improved)
		{
			$category = new Category($this->context->shop->getCategory(), $this->context->language->id);
			$home_featured_products = $this->wrapProducts($category->getProducts((int)Context::getContext()->language->id, 1,
			(Configuration::get('HOME_FEATURED_NBR') ? (int)Configuration::get('HOME_FEATURED_NBR') : 8), 'position'), array(), true);
                        //d($home_featured_products);
			foreach ($home_featured_products as $product){
                            //d($product);
                            if($onlyJS){
                                $outputJS .= $tracker."('ec:addImpression', ".  json_encode($product).");";
                            }else{
                                $data['il0pi'.$nbProducts.'id'] = urlencode($product['id']);
                                $data['il0pi'.$nbProducts.'nm'] = urlencode($product['name']);
                                $data['il0pi'.$nbProducts.'br'] = urlencode($product['brand']);
                                $data['il0pi'.$nbProducts.'ca'] = urlencode($product['category']);
                                $data['il0pi'.$nbProducts.'va'] = urlencode($product['variant']);
                                $data['il0pi'.$nbProducts.'pr'] = urlencode($product['price']);
                                $data['il0pi'.$nbProducts.'ps'] = urlencode($product['position']);
                                $nbProducts++;
                            }
                        }
                        
		}

		// New products
                $nbProducts = 0;
		if (Module::isInstalled('blocknewproducts') && Module::isEnabled('blocknewproducts') && $ecomerce_improved && (Configuration::get('PS_NB_DAYS_NEW_PRODUCT')
				|| Configuration::get('PS_BLOCK_NEWPRODUCTS_DISPLAY')))
		{
			$new_products = Product::getNewProducts((int)$this->context->language->id, 0, (int)Configuration::get('NEW_PRODUCTS_NBR'));
			$new_products_list = $this->wrapProducts($new_products, array(), true);
			foreach ($new_products_list as $product){
                            if($onlyJS){
                                $outputJS .= $tracker."('ec:addImpression', ".  json_encode($product).");";
                            }else{
                                $data['il1pi'.$nbProducts.'id'] = urlencode($product['id']);
                                $data['il1pi'.$nbProducts.'nm'] = urlencode($product['name']);
                                $data['il1pi'.$nbProducts.'br'] = urlencode($product['brand']);
                                $data['il1pi'.$nbProducts.'ca'] = urlencode($product['category']);
                                $data['il1pi'.$nbProducts.'va'] = urlencode($product['variant']);
                                $data['il1pi'.$nbProducts.'pr'] = urlencode($product['price']);
                                $data['il1pi'.$nbProducts.'ps'] = urlencode($product['position']);
                                $nbProducts++;
                            }
                        }
		}

		// Best Sellers
                $nbProducts = 0;
		if (Module::isInstalled('blockbestsellers') && Module::isEnabled('blockbestsellers') && $ecomerce_improved && (!Configuration::get('PS_CATALOG_MODE')
				|| Configuration::get('PS_BLOCK_BESTSELLERS_DISPLAY')))
		{
			$ga_homebestsell_product_list = $this->wrapProducts(ProductSale::getBestSalesLight((int)$this->context->language->id, 0, 8), array(), true);
			foreach ($ga_homebestsell_product_list as $product){
                            if($onlyJS){
                                $outputJS .= $tracker."('ec:addImpression', ".  json_encode($product).");";
                            }else{
                                $data['il2pi'.$nbProducts.'id'] = urlencode($product['id']);
                                $data['il2pi'.$nbProducts.'nm'] = urlencode($product['name']);
                                $data['il2pi'.$nbProducts.'br'] = urlencode($product['brand']);
                                $data['il2pi'.$nbProducts.'ca'] = urlencode($product['category']);
                                $data['il2pi'.$nbProducts.'va'] = urlencode($product['variant']);
                                $data['il2pi'.$nbProducts.'pr'] = urlencode($product['price']);
                                $data['il2pi'.$nbProducts.'ps'] = urlencode($product['position']);
                                $nbProducts++;
                            }
                        }
		}
                
                
                break;
            case "category":
                
                break;
            case "product":
                
                break;
            
                
            //demas casos    
        }
        }else{
            
            $data['ti'] = $params['order']->id;
            $data['ta'] = urlencode($this->context->shop->name);
            $data['tr'] = $params['order']->total_paid;
            $data['ts'] = $params['order']->total_shipping;
            $data['tt'] = $params['order']->total_paid_tax_incl-$params['order']->total_paid_tax_excl;
            $orderCoupons = Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'order_cart_rule WHERE id_order = '.$data['ti']);
            $coupons = array();
            foreach($orderCoupons as $c){
                $voucher = new CartRule($c['id_cart_rule']);
                $coupons[] = $voucher->code;
            }
            if(count($coupons)>0){
                $data['tcc'] = urlencode(implode('/', $coupons));
            }
            
            $products = $params['cart']->getProducts();
            
            $data['iq'] = count($products);
            
            $currency = new Currency($params['cart']->id_currency);
            
            $data['cu'] = urlencode($currency->iso_code);
            
            $usetax = (Product::getTaxCalculationMethod((int)$params['order']->id_customer) != PS_TAX_EXC);
            
            if(!$ecomerce_improved){
                $transactionData = array();
                // Send Transaction hit
                $transactionData = array(
                  'v' => $v,
                  'tid' => $tid,
                  'cid' => $cid,
                  't' => 'transaction',
                  'ti' => $data['ti'],
                  'ta' => $data['ta'],
                  'tr' => $data['tr'],
                  'ts' => $data['ts'],
                  'tt' => $data['tt'],
                  'cu' => $data['cu']
                );
                $this->gaFireHit($transactionData);
            }
            
            $nbProducts = 1;
            foreach($products as $product){
                
                $product_id = 0;
		if (!empty($product['id_product']))
			$product_id = $product['id_product'];
		else if (!empty($product['id']))
			$product_id = $product['id'];
			
		if (!empty($product['id_product_attribute']))
			$product_id .= '-'. $product['id_product_attribute'];
                
                $data['pr'.$nbProducts.'id'] = urlencode($product_id);
                $data['pr'.$nbProducts.'nm'] = urlencode($product['name']);
                $data['pr'.$nbProducts.'br'] = urlencode(($product['manufacturer_name']) ? Tools::jsonEncode($product['manufacturer_name']) : '');
                $data['pr'.$nbProducts.'ca'] = urlencode($product['category']);
                
                $variant = null;
		if (isset($product['attributes_small']))
			$variant = $product['attributes_small'];
		elseif (isset($extras['attributes_small']))
			$variant = $extras['attributes_small'];
                
                $data['pr'.$nbProducts.'va'] = urlencode($variant);
                $data['pr'.$nbProducts.'pr'] = Product::getPriceStatic((int)$product['id_product'], $usetax);
                $data['pr'.$nbProducts.'qt'] = urlencode($product['cart_quantity']);
                $data['pr'.$nbProducts.'ps'] = urlencode($nbProducts);
                
                $data['pa'] = urlencode('purchase');
                $data['col'] = urlencode($params['order']->payment);
                
                
                if(!$ecomerce_improved){
                    $itemData = array();
                    // Send Transaction hit
                    $in = $data['pr'.$nbProducts.'nm'];
                    $ip = $data['pr'.$nbProducts.'pr'];
                    $iq = $data['pr'.$nbProducts.'qt'];
                    $ic = $data['pr'.$nbProducts.'id']; // item SKU
                    $iv = $data['pr'.$nbProducts.'ca']; // Product Category - we use 'SI' in all cases, you may not want to
                    $itemData = array(
                        'v' => $v,
                        'tid' => $tid,
                        'cid' => $cid,
                        't' => 'item',
                        'ti' => $transactionData['ti'],
                        'in' => $in,
                        'ip' => $ip,
                        'iq' => $iq,
                        'ic' => $ic,
                        'iv' => $iv,
                        'cu' => $transactionData['cu']
                    );
                    $this->gaFireHit($itemData);
                }
                $nbProducts++;
            }


            if(!$ecomerce_improved){
                return '';
            }


        }
        
        
        //Estadisticas
        
        
        if($onlyJS){
            $outputJS  .=  $tracker.'(\'set\', \'userId\', \''.$uid.'\');';
            $outputJS  .=  $tracker.'(\'set\', \'dimension1\', \'Prestashop\');';
            $outputJS  .= $tracker.'(\'send\', \'pageview\');';
        }else{
                
                $data['v'] = $v;
                $data['tid'] = $tid;
                $data['cid'] = $cid;
                $data['uid'] = $uid;
                $data['t'] = urlencode('pageview');
                $data['dt'] = urlencode($this->context->smarty->getVariable('meta_title')->value);
				$data['dp'] = urlencode('order-confirmation');
            
        $document_url = Configuration::get('WIM_GANALYTICS_DOCUMENT_URL');
        switch ($document_url){
            case 0:
                break;
            case 1:
                $data['dl'] = $url;
                break;
            case 2:
                $data['dh'] = $host;
                $data['dp'] = $uri;
                break;
            default:
                $data['dl'] = $url;
        }
            
            if($userIP) $data['uip'] = $userIP;
            if($userAgent) $data['ua'] = $userAgent;
            if($userLocation) $data['geoid'] = $userLocation;
            if($referrer) $data['dr'] = $referrer;

            if($this->context->controller->controller_name == 'AdminOrders'){
                $data['cs'] = 'AdminOrders';
                $data['cm'] = 'Admin';
                $data['cn'] = $this->context->employee->email;
            }
            $data['cd1'] = 'Prestashop';
            $data['cd2'] = $params['order']->module;


            $outputHTTP[] = $data;
        }
            
        foreach ($outputHTTP as $fields)
            $this->sendHTTPRequest ($fields);
        
        
        return $outputJS;
    }
    
    
    public function wrapProducts($products, $extras = array(), $full = false)
	{
		$result_products = array();
		if (!is_array($products))
			return;

		$currency = new Currency($this->context->currency->id);
		$usetax = (Product::getTaxCalculationMethod((int)$this->context->customer->id) != PS_TAX_EXC);

		if (count($products) > 20)
			$full = false;
		else
			$full = true;

		foreach ($products as $index => $product)
		{
			if ($product instanceof Product)
				$product = (array)$product;

			if (!isset($product['price']))
				$product['price'] = (float)Product::getPriceStatic((int)$product['id_product'], $usetax);
			$result_products[] = $this->wrapProduct($product, $extras, $index, $full);
		}

		return $result_products;
	}
        
        public function wrapProduct($product, $extras, $index = 0, $full = false)
	{
		$ga_product = '';

		$variant = null;
		if (isset($product['attributes_small']))
			$variant = $product['attributes_small'];
		elseif (isset($extras['attributes_small']))
			$variant = $extras['attributes_small'];

		$product_qty = 1;
		if (isset($extras['qty']))
			$product_qty = $extras['qty'];
		elseif (isset($product['cart_quantity']))
			$product_qty = $product['cart_quantity'];

		$product_id = 0;
		if (!empty($product['id_product']))
			$product_id = $product['id_product'];
		else if (!empty($product['id']))
			$product_id = $product['id'];
			
		if (!empty($product['id_product_attribute']))
			$product_id .= '-'. $product['id_product_attribute'];

		$product_type = 'typical';
		if (isset($product['pack']) && $product['pack'] == 1)
			$product_type = 'pack';
		elseif (isset($product['virtual']) && $product['virtual'] == 1)
			$product_type = 'virtual';

		if ($full)
		{
			$ga_product = array(
				'id' => $product_id,
				'name' => Tools::jsonEncode($product['name']),
				'category' => Tools::jsonEncode($product['category']),
				'brand' => isset($product['manufacturer_name']) ? Tools::jsonEncode($product['manufacturer_name']) : '',
				'variant' => Tools::jsonEncode($variant),
				'type' => $product_type,
				'position' => $index ? $index : '0',
				'quantity' => $product_qty,
				'list' => Tools::getValue('controller'),
				'url' => isset($product['link']) ? urlencode($product['link']) : '',
				'price' => number_format($product['price'], '2')
			);
		}
		else
		{
			$ga_product = array(
				'id' => $product_id,
				'name' => Tools::jsonEncode($product['name'])
			);				
		}
		return $ga_product;
	}
        
        
        function gaFireHit( $data = null ) {
            if ( $data ) {
                $getString = 'https://ssl.google-analytics.com/collect';
                $getString .= '?payload_data&';
                $getString .= http_build_query($data);
                $result = file_get_contents( $getString );
                //echo $getString;
                #$sendlog = error_log($getString, 1, "ME@EMAIL.COM"); // comment this in and change your email to get an log sent to your email

                return $result;
            }
            return false;
        }
}
