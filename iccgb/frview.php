<?php
 
require_once('../../config.php');
require_once('full_ranking.php');
 
global $DB, $OUTPUT, $PAGE;
 
// Check for all required and optional variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_iccgb', $courseid);
}

// Configures and validates the current $PAGE 
require_login($course);
$PAGE->set_url('/blocks/iccgb/frview.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('full_ranking', 'block_iccgb'));

$settingsnode = $PAGE->settingsnav->add(get_string('full_ranking', 'block_iccgb'));
$editurl = new moodle_url('/blocks/iccgb/frview.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('full_ranking', 'block_iccgb'), $editurl);
$editnode->make_active();
 
// Creates, fills and shows the form
$full_ranking = new full_ranking();
$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;

// Form didn't validate or this is the first display
$site = get_site();
echo $OUTPUT->header();
$full_ranking->display();
echo $OUTPUT->footer();

?>