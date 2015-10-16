<?php

require_once("{$CFG->libdir}/formslib.php");
require_once('lib.php');
 
class levels_rewards_info extends moodleform {
 
    function definition() {
    	global $CFG, $OUTPUT, $COURSE, $DB;

        $mform =& $this->_form;

		// Includes CSS file;
        echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/blocks/iccgb/styles.css">';

        $coursecontext = context_course::instance($COURSE->id);
        $blockinstance = $DB->get_record('block_instances', array('blockname' => 'iccgb', 'parentcontextid' => $coursecontext->id));
        $iccgb_instance = block_instance('iccgb', $blockinstance);
        $cfg = $iccgb_instance->config;

        $content = isset($cfg->levels_rewards['text']) ? $cfg->levels_rewards['text'] : '';
        $mform->addElement('html', $content);

        // Hidden elements
        $mform->addElement('hidden', 'blockid');
        $mform->setType('blockid', PARAM_INT);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
    }
}

?>