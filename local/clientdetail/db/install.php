<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_clientdetail_install() {
  global $DB, $CFG;

  $context = context_system::instance();

  // Check if the "Client Detail" custom field category exists.
  $categoryname = 'Client Detail';
  $categorycomponent = 'core_course';

  if (!$DB->record_exists('customfield_category', ['name' => $categoryname, 'component' => $categorycomponent])) {
    $category = new \core_customfield\category(0, (object)[
      'name' => $categoryname,
      'component' => $categorycomponent,
      'area' => 'course',
      'contextid' => $context->id,
    ]);
    $category->save();
    $categoryid = $category->get('id');
    echo "Custom field category 'Client Detail' created.<br>";
  } else {
    $categoryid = $DB->get_field('customfield_category', 'id', ['name' => $categoryname, 'component' => $categorycomponent]);
    echo "Custom field category 'Client Detail' already exists.<br>";
  }

  $clientname_shortname = 'clientname';
  $clientid_shortname = 'clientid';

  if (!$DB->record_exists('customfield_field', ['shortname' => $clientname_shortname, 'categoryid' => $categoryid])) {
    $field = \core_customfield\field_controller::create(0, (object)[
      'shortname' => $clientname_shortname,
      'name' => 'Client Name',
      'type' => 'text',
      'categoryid' => $categoryid,
      'configdata' => json_encode([
        'defaultvalue' => '',
        'required' => 0,
        'uniquevalues' => 0,
        'maxlength' => 255,
      ]),
    ]);
    $field->save();
    echo "Custom field 'Client Name' created.<br>";
  } else {
    echo "Custom field 'Client Name' already exists.<br>";
  }

  if (!$DB->record_exists('customfield_field', ['shortname' => $clientid_shortname, 'categoryid' => $categoryid])) {
    $field = \core_customfield\field_controller::create(0, (object)[
      'shortname' => $clientid_shortname,
      'name' => 'Client ID',
      'type' => 'text',
      'categoryid' => $categoryid,
      'configdata' => json_encode([
        'defaultvalue' => '',
        'required' => 0,
        'uniquevalues' => 0,
        'maxlength' => 255,
      ]),
    ]);
    $field->save();
    echo "Custom field 'Client ID' created.<br>";
  } else {
    echo "Custom field 'Client ID' already exists.<br>";
  }
}
