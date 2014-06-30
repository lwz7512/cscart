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


if ( !defined('BOOTSTRAP') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

}

if ($mode == 'upgrade') {
	$addon = 'faq';
	$rewrite_opts = array (
		'ratio'
	);
	$xml = simplexml_load_file(Registry::get('config.dir.addons') . $addon . '/addon.xml');
	
	if (isset($xml->opt_settings)) {
		$options = db_get_field("SELECT options FROM ?:addons WHERE addon = ?s", $addon);
		$options = fn_parse_addon_options($options);
		$sections_node = isset($xml->opt_settings->section) ? $xml->opt_settings->section : $xml->opt_settings;
		foreach ($sections_node as $section) {
			foreach ($section->item as $item) {
				if (!empty($item->name)) {
					if (isset($options[(string)$item['id']]) && !in_array((string)$item['id'], $rewrite_opts)) {
						continue;
					}
					if ((string)$item->type != 'header') {
						if (isset($item->multilanguage)) {
							$options[(string)$item['id']] = '%ML%';

							$ml_option_value = array(
								'addon' => $addon,
								'object_id' => (string)$item['id'],
								'object_type' => 'L', // option value
								'description' => (string)$item->default_value
							);

							foreach ((array)Registry::get('languages') as $ml_option_value['lang_code'] => $_v) {
								$ml_option_value['description'] = isset($options[(string)$item['id']]) ? $options[(string)$item['id']] : (string)$item->default_value;
								foreach ($item->multilanguage->item as $v_item) {
									if ((string)$v_item['lang'] == $ml_option_value['lang_code']) {
										$ml_option_value['description'] = (string)$v_item;
									}
								}
								db_query("REPLACE INTO ?:addon_descriptions ?e", $ml_option_value);
							}
						} else {
							$options[(string)$item['id']] = isset($options[(string)$item['id']]) ? $options[(string)$item['id']] : (string)$item->default_value;
						}
					}
					$descriptions = array(
						'addon' => $addon,
						'object_id' => (string)$item['id'],
						'object_type' => 'O', //option
					);
					foreach ((array)Registry::get('languages') as $descriptions['lang_code'] => $_v) {
						$descriptions['description'] = (string)$item->name;

						if (isset($item->tooltip)) {
							$descriptions['tooltip'] = (string)$item->tooltip;
							if (isset($item->tt_translations)) {
								foreach ($item->tt_translations->item as $_item) {
									if ((string)$_item['lang'] == $descriptions['lang_code']) {
										$descriptions['tooltip'] = (string)$_item;
									}
								}
							}
						}
						if (isset($item->translations)) {
							foreach ($item->translations->item as $_item) {
								if ((string)$_item['lang'] == $descriptions['lang_code']) {
									$descriptions['description'] = (string)$_item;
								}
							}
						}
						db_query("REPLACE INTO ?:addon_descriptions ?e", $descriptions);
					}
					if (isset($item->variants)) {
						foreach ($item->variants->item as $vitem) {
							$descriptions = array(
								'addon' => $addon,
								'object_id' => (string)$vitem['id'],
								'object_type' => 'V', //variant
							);

							foreach ((array)Registry::get('languages') as $descriptions['lang_code'] => $_v) {
								$descriptions['description'] = (string)$vitem->name;
								if (isset($vitem->translations)) {
									foreach ($vitem->translations->item as $_vitem) {
										if ((string)$_vitem['lang'] == $descriptions['lang_code']) {
											$descriptions['description'] = (string)$_vitem;
										}
									}
								}
								db_query("REPLACE INTO ?:addon_descriptions ?e", $descriptions);
							}
						}
					}
				}
			}
		}
		db_query("UPDATE ?:addons SET options = ?s WHERE addon = ?s", serialize($options), $addon);
	}
	if (isset($xml->opt_language_variables)) {
		$cache = array();
		foreach ($xml->opt_language_variables->item as $v) {
			$descriptions = array(
				'lang_code' => (string)$v['lang'],
				'name' => (string)$v['id'],
				'value' => (string)$v,
			);

			$cache[$descriptions['name']][$descriptions['lang_code']] = $descriptions['value'];

			$row = db_get_field("SELECT name FROM ?:language_values WHERE name = ?s AND lang_code = ?s", $descriptions['name'], $descriptions['lang_code']);
			if (empty($row)) {
				db_query("INSERT INTO ?:language_values ?e", $descriptions);
			} elseif (in_array($row, $rewrite_vars)) {
				db_query("REPLACE INTO ?:language_values ?e", $descriptions);
			}
		}

		// Add variables for missed languages
		$_all_languages = Registry::get('languages');
		$_all_languages = array_keys($_all_languages);
		foreach ($cache as $n => $lcs) {
			$_lcs = array_keys($lcs);

			$missed_languages = array_diff($_all_languages, $_lcs);
			if (!empty($missed_languages)) {
				$descriptions = array(
					'name' => $n,
					'value' => $lcs['EN'],
				);

				foreach ($missed_languages as $descriptions['lang_code']) {
					$row = db_get_field("SELECT name FROM ?:language_values WHERE name = ?s AND lang_code = ?s", $descriptions['name'], $descriptions['lang_code']);
					if (empty($row)) {
						db_query("REPLACE INTO ?:language_values ?e", $descriptions);
					}
				}
			}
		}
	}

	// Install templates
	$areas = array('customer', 'admin', 'mail');
	$installed_skins = fn_get_dir_contents(Registry::get('config.dir.skins'));
	foreach ($installed_skins as $skin_name) {
		foreach ($areas as $area) {
			if (is_dir(Registry::get('config.dir.skins')_REPOSITORY . 'base/' . $area . '/addons/' . $addon)) {
				fn_rm(Registry::get('config.dir.skins') . $skin_name . '/' . $area . '/addons/' . $addon);
				fn_copy(Registry::get('config.dir.skins')_REPOSITORY . 'base/' . $area . '/addons/' . $addon, Registry::get('config.dir.skins') . $skin_name . '/' . $area . '/addons/' . $addon);
			}
		}
	}

	$faq_data = array(
		'option_name' => 'faq_version',
		'option_type' => 'I',
		'value' => FAQ_VERSION
	);
	$faq_option_id = db_get_field("SELECT option_id FROM ?:settings WHERE option_name = ?s", 'faq_version');
	if (!empty($faq_option_id)) {
		db_query("UPDATE ?:settings SET ?u WHERE option_id = ?i", $faq_data, $faq_option_id);
	} else {
		db_query("REPLACE INTO ?:settings ?e", $faq_data);
	}

	fn_rm(DIR_COMPILED, false);
	fn_rm(DIR_CACHE, false);
	Registry::cleanup();

	fn_set_notification('N', __('notice'), __('upgrade_completed'));

	return array(CONTROLLER_STATUS_REDIRECT, "faq_manager.manage");
}

?>
