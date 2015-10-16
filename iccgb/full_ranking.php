<?php

require_once("{$CFG->libdir}/formslib.php");
require_once('lib.php');
 
class full_ranking extends moodleform {
 
    function definition() {
    	global $CFG, $OUTPUT, $COURSE;

        $mform =& $this->_form;

		// Includes CSS file;
        echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/blocks/iccgb/styles.css">';

        $students = block_get_students();
        $mform->addElement('html', block_print_ranking($students, count($students)));

        // Hidden elements
		$mform->addElement('hidden', 'blockid');
		$mform->setType('blockid', PARAM_INT);
		$mform->addElement('hidden', 'courseid');
		$mform->setType('courseid', PARAM_INT);
    }
}

?>