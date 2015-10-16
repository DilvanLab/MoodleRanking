<?php
 
require_once('../../config.php');
require_once('level_config_form.php');
 
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
$PAGE->set_url('/blocks/iccgb/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('lvl_config', 'block_iccgb'));

$settingsnode = $PAGE->settingsnav->add(get_string('lvl_config', 'block_iccgb'));
$editurl = new moodle_url('/blocks/iccgb/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
$editnode = $settingsnode->add(get_string('lvl_config', 'block_iccgb'), $editurl);
$editnode->make_active();
 
// Creates, fills and shows the form
$levels_form = new level_config_form();
$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;

$fields = $DB->get_record('block_iccgb', array('courseid' => $courseid));
if($fields) {
	foreach($fields as $field => $value) {
		$toform[$field] = $value;
	}
}
$levels_form->set_data($toform);

// Cancelled forms redirect to the course main page.
if($levels_form->is_cancelled()) {
    $courseurl = new moodle_url('/course/view.php', array('id' => $id));
    redirect($courseurl);
// The block hasn't been set for such course
} else if ($fromform = $levels_form->get_data()) {
	// Configurations to the course already exist
	if(!$fields) {
		if (!$DB->insert_record('block_iccgb', $fromform)) {
		    print_error('inserterror', 'block_iccgb');
		}
	}
	else {
		$fromform->id = $fields->id;
		if (!$DB->update_record('block_iccgb', $fromform)) {
		    print_error('updateerror', 'block_iccgb');
		}
	}

	// Saves each level's badge
	for($i = 0; $i < levels_used($courseid); $i++) {
		$filename = 'c'.$courseid.'l'.($i+1);
		$dir = $CFG->dirroot.'/blocks/iccgb/lvbadges/'.$filename;
		$success = $levels_form->save_file('lvl'.($i+1).'_badge', $dir, true);
	}

    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);
} else {
    // Form didn't validate or this is the first display
    $site = get_site();
    echo $OUTPUT->header();
    $levels_form->display();
    echo $OUTPUT->footer();
}



?>