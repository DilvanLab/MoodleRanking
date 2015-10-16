<?php

require_once("{$CFG->libdir}/formslib.php");
require_once('lib.php');
 
class level_config_form extends moodleform {
 
    function definition() {
    	global $CFG, $OUTPUT, $COURSE;

        $mform =& $this->_form;

		// Includes CSS file;
        echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/blocks/iccgb/styles.css">';

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('lvl_config', 'block_iccgb'));
        $mform->addElement('html', '<table id="editform_lct" class="level_conf_table">');
        
        // Levels configuration instructions
        $text = get_string('level_instructions','block_iccgb');
        $tag = html_writer::div($text);
        $mform->addElement('html', $tag);
        $mform->addElement('html', '<br>');

        $picdir = $CFG->wwwroot.'/blocks/iccgb/lvbadges/';
        for($i = 0; $i < MAX_LEVEL; $i++) {
            $picname = 'c'.$COURSE->id.'l'.($i+1);

            // if($i % 2 == 0) {
                $mform->addElement('html', '<tr class="level_conf_table_row">');
            // }
            
            $mform->addElement('html', '<td class="badge_cell">');
            $mform->addElement('html', '<object data="'.$picdir.$picname.'" class="badge_image" type="image/png">
            	<img src="'.$picdir.'default'.($i+1).'.png" class="badge_image"  /></object>');
            $mform->addElement('html', '</td>');

            $fieldname = 'config_iccgb_lvl_'.($i+1).'exp';
            $mform->addElement('html', '<td class="level_field_cell">');
            $mform->addElement('text', $fieldname, 'Level '.($i+1));
            $mform->setDefault($fieldname, 0);
            $mform->setType($fieldname, PARAM_INT);
            $mform->addElement('html', '</td>');

        	$badge_name = 'lvl'.($i+1).'_badge';
            $mform->addElement('html', '<td class="file_cell">');
            $mform->addElement('filepicker', $badge_name, '', null, array('accepted_types' => '*'));
            $mform->addElement('html', '</td>');

            // if($i % 2 != 0) {
                $mform->addElement('html', '</tr>');
            // }

        }
        $mform->addElement('html', '</table>');

        // Hidden elements
		$mform->addElement('hidden', 'blockid');
		$mform->setType('blockid', PARAM_INT);
		$mform->addElement('hidden', 'courseid');
		$mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons();
    }
}

?>