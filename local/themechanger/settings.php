<?php
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

if ($hassiteconfig) {
  $settings = new admin_settingpage('local_themechanger', get_string('pluginname', 'local_themechanger'));

  $themes = core_component::get_plugin_list('theme');
  $themelist = array();

  foreach ($themes as $themename => $themedir) {
    $themelist[$themename] = $themename;
  }

  $settings->add(new admin_setting_configselect(
    'local_themechanger/theme',
    get_string('selecttheme', 'local_themechanger'),
    get_string('selecttheme_desc', 'local_themechanger'),
    'boost',
    $themelist
  ));

  $ADMIN->add('localplugins', $settings);

  local_themechanger_after_config();
}
