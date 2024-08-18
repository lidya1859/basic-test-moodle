<?php
defined('MOODLE_INTERNAL') || die();

function local_themechanger_after_config() {
  global $CFG;

  $theme = get_config('local_themechanger', 'theme');

  if ($theme && $theme != $CFG->theme) {
    theme_reset_all_caches();
    $CFG->theme = $theme;
    $theme = theme_config::load($theme);
    if ($theme instanceof \theme_config) {
      set_config('theme', $theme->name);
      $notifytype = 'success';
      $notifymessage = get_string('themesaved');
    } else {
      $notifytype = 'error';
      $notifymessage = get_string('error');
    }
  }
}
