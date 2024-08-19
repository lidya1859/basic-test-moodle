<?php
defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

class local_clientdetail_external extends external_api {

  public static function set_client_data_parameters() {
    return new external_function_parameters(
      [
        'courseid' => new external_value(PARAM_INT, 'Course ID'),
        'clientname' => new external_value(PARAM_TEXT, 'Client Name'),
        'clientid' => new external_value(PARAM_TEXT, 'Client ID')
      ]
    );
  }

  public static function set_client_data($courseid, $clientname, $clientid) {
    global $DB;

    $params = self::validate_parameters(self::set_client_data_parameters(), ['courseid' => $courseid, 'clientname' => $clientname, 'clientid' => $clientid]);
    
    $handler = \core_course\customfield\course_handler::create();
    $context = context_course::instance($params['courseid']);
    $data = $handler->get_instance_data($params['courseid'], true);

    $clientname_fielddata = null;
    $clientid_fielddata = null;

    foreach ($data as $fielddata) {
      if ($fielddata->get_field()->get('shortname') === 'clientname') {
        $clientname_fielddata = $fielddata;
      } elseif ($fielddata->get_field()->get('shortname') === 'clientid') {
        $clientid_fielddata = $fielddata;
      }
    }

    // Handle client name field
    if ($clientname_fielddata && $clientname_fielddata->get('id')) {
      $clientname_fielddata->set($clientname_fielddata->datafield(), $params['clientname']);
    } else {
      $clientname_field = $DB->get_record('customfield_field', ['shortname' => 'clientname']);
      $clientname_fielddata = \core_customfield\data_controller::create(0, (object)[
        'instanceid' => $params['courseid'],
        'fieldid' => $clientname_field->id,
        'value' => $params['clientname'],
        'charvalue' => $params['clientname'],
        'contextid' => $context->id
      ]);
    }
    $clientname_fielddata->save();

    // Handle client ID field
    if ($clientid_fielddata && $clientid_fielddata->get('id')) {
      $clientid_fielddata->set($clientid_fielddata->datafield(), $params['clientid']);
    } else {
      $clientid_field = $DB->get_record('customfield_field', ['shortname' => 'clientid']);
      $clientid_fielddata = \core_customfield\data_controller::create(0, (object)[
        'instanceid' => $params['courseid'],
        'fieldid' => $clientid_field->id,
        'value' => $params['clientid'],
        'charvalue' => $params['clientid'],
        'contextid' => $context->id
      ]);
    }
    $clientid_fielddata->save();

    return true;
  }

  public static function set_client_data_returns() {
    return new external_value(PARAM_BOOL, 'Returns true on success');
  }

  public static function get_client_data_parameters() {
    return new external_function_parameters(
      [
        'courseid' => new external_value(PARAM_INT, 'Course ID')
      ]
    );
  }

  public static function get_client_data($courseid) {
    $params = self::validate_parameters(self::get_client_data_parameters(), ['courseid' => $courseid]);

    $handler = \core_course\customfield\course_handler::create();
    $data = $handler->get_instance_data($params['courseid'], true);

    $result = new stdClass();
    $result->clientname = '';
    $result->clientid = '';
    foreach ($data as $fielddata) {
      if ($fielddata->get_field()->get('shortname') === 'clientname') {
        $result->clientname = $fielddata->get_value();
      } elseif ($fielddata->get_field()->get('shortname') === 'clientid') {
        $result->clientid = $fielddata->get_value();
      }
    }

    return $result;
  }

  public static function get_client_data_returns() {
    return new external_single_structure(
      [
        'clientname' => new external_value(PARAM_TEXT, 'Client Name'),
        'clientid' => new external_value(PARAM_TEXT, 'Client ID')
      ]
    );
  }
}
