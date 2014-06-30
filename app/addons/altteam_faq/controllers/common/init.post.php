<?php
/*****************************************************************************
 * This is a commercial software, only users who have purchased a  valid
 * license and accepts the terms of the License Agreement can install and use  
 * this program.
 *----------------------------------------------------------------------------
 * @copyright  LCC Alt-team: http://www.alt-team.com
 * @module     "Alt-team: FAQ"
 * @version    faq_4_1.1.2
 * @license    http://www.alt-team.com/addons-license-agreement.html
 ****************************************************************************/

use Tygh\Registry;

if (isset($_REQUEST['wibug']) && (empty($_REQUEST['wibug']) || $_REQUEST['wibug'] == 'Y' || $_REQUEST['wibug'] == 'faq')) {

	fn_set_wibug_message_faq('
		Alt-team: FAQ add-on
		Version: faq_4_1.1.2.13
		Hi, Irlandec! ;)
		------------------------------
	');
	
	if (!defined("WIBUG")) { define('WIBUG', true); }

}
