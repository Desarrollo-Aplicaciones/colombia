/**
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
*/
$(document).ready(function()
{
    $(document).on('click', '.fancybox', function(e){
        e.preventDefault();
    });

    $("a.fancybox").fancybox({
        'hideOnContentClick': true,
        'openEffect'    : 'elastic',
        'closeEffect'   : 'elastic'
    });

    $(".textarea-autosize").autosize();
});