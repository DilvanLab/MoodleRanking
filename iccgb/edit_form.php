<?php


defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class block_iccgb_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG, $OUTPUT, $COURSE, $PAGE;

        // Includes CSS file;
        echo '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/blocks/iccgb/styles.css">';

        // Check for all required and optional variables.
        $courseid = required_param('id', PARAM_INT);
        $block_instance_id = required_param('bui_editid', PARAM_INT);

        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('block_settings', 'block_iccgb'));

        // Activities configuration instructions
        $mform->addElement('html', '<br>');
        $text = get_string('activities_instructions','block_iccgb');
        $tag = html_writer::div($text);
        $mform->addElement('html', $tag);

        // Completion experience reward configurations
        $mform->addElement('text', 'config_resourcepoints', get_string('resourcepoints', 'block_iccgb'));
        $mform->setDefault('config_resourcepoints', '2');
        $mform->setType('config_resourcepoints', PARAM_FLOAT);

        $mform->addElement('text', 'config_assignpoints', get_string('assignpoints', 'block_iccgb'));
        $mform->setDefault('config_assignpoints', '2');
        $mform->setType('config_assignpoints', PARAM_FLOAT);

        $mform->addElement('text', 'config_forumpoints', get_string('forumpoints', 'block_iccgb'));
        $mform->setDefault('config_forumpoints', '2');
        $mform->setType('config_forumpoints', PARAM_FLOAT);

        $mform->addElement('text', 'config_pagepoints', get_string('pagepoints', 'block_iccgb'));
        $mform->setDefault('config_pagepoints', '2');
        $mform->setType('config_pagepoints', PARAM_FLOAT);

        $mform->addElement('text', 'config_workshoppoints', get_string('workshoppoints', 'block_iccgb'));
        $mform->setDefault('config_workshoppoints', '2');
        $mform->setType('config_workshoppoints', PARAM_FLOAT);

        $mform->addElement('text', 'config_quizpoints', get_string('quizpoints', 'block_iccgb'));
        $mform->setDefault('config_quizpoints', '2');
        $mform->setType('config_quizpoints', PARAM_FLOAT);

        $mform->addElement('text', 'config_defaultpoints', get_string('defaultpoints', 'block_iccgb'));
        $mform->setDefault('config_defaultpoints', '2');
        $mform->setType('config_defaultpoints', PARAM_FLOAT);

        // Weights configuration instructions
        $mform->addElement('html', '<br>');
        $text = get_string('weight_instructions','block_iccgb');
        $tag = html_writer::div($text, 'instruction_text');
        $mform->addElement('html', $tag);

        // Coefficients configuration fields
        $mform->addElement('text', 'config_grades_weight', get_string('grades_weight', 'block_iccgb'));
        $mform->setDefault('config_grades_weight', '1');
        $mform->setType('config_grades_weight', PARAM_FLOAT);

        $mform->addElement('text', 'config_completion_weight', get_string('completion_weight', 'block_iccgb'));
        $mform->setDefault('config_completion_weight', '1');
        $mform->setType('config_completion_weight', PARAM_FLOAT);

        // Formula
        $mform->addElement('html', '<br>');
        $text = get_string('formula','block_iccgb');
        $tag = html_writer::div($text, 'instruction_text');
        $mform->addElement('html', $tag);
        $mform->addElement('html', '<br>');

        // Ranking size
        $mform->addElement('text', 'config_rankingsize', get_string('rankingsize', 'block_iccgb'));
        $mform->setDefault('config_rankingsize', '5');
        $mform->setType('config_rankingsize', PARAM_INT);
        $mform->addElement('html', '<br>');

        // Levels rewards
        $mform->addElement('editor', 'config_levels_rewards', get_string('levels_rewards', 'block_iccgb'));
        $mform->setType('config_levels_rewards', PARAM_RAW);
        $mform->addElement('html', '<br>');

        // Adds the link to levels configuration form
        $pageparam = array('blockid' => $block_instance_id, 
              'courseid' => $COURSE->id);
        $editurl = new moodle_url('/blocks/iccgb/view.php', $pageparam);
        $editurlpic = new moodle_url('/pix/t/edit.png');
        $edit = html_writer::link($editurl, html_writer::tag('img', get_string('lvl_config', 'block_iccgb'), array('src' => $editurlpic)));
        $mform->addElement('html', $edit);
    }
}