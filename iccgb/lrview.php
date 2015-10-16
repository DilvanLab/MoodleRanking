<?php
 
require_once('../../config.php');
require_once('levels_rewards_info.php');
 
global $DB, $OUTPUT, $PAGE;

// Check for all required and optional variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_iccgb', $courseid);
}

// Configures and validates the current $PAGE 
require_login($course);
$PAGE->set_url('/blocks/iccgb/lrview.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('levels_rewards_config', 'block_iccgb'));

$settingsnode = $PAGE->settingsnav->add(get_string('levels_rewards_instructions', 'block_iccgb'));
$editurl = new moodle_url('/blocks/iccgb/lrview.php');
$editnode = $settingsnode->add(get_string('levels_rewards_config', 'block_iccgb'), $editurl);
$editnode->make_active();

// Creates, fills and shows the form
$levels_rewards_info = new levels_rewards_info();
$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;

// Form didn't validate or this is the first display
$site = get_site();
echo $OUTPUT->header();
$levels_rewards_info->display();
echo $OUTPUT->footer();

?>