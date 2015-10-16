<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/blocks/iccgb/lib.php');

class block_iccgb extends block_base {	
    public function init() {
    	$this->title = get_string('blockname', 'block_iccgb');
    }

    public function specialization() {
        $title = isset($this->config->ranking_title) ? trim($this->config->ranking_title) : '';
        if (!empty($title)) {
            $this->title = format_string($this->config->ranking_title);
        }
    }

    public function applicable_formats() {
        return array(
            'course-view'    => true,
            'site'           => false,
            'mod'            => false,
            'my'             => false
        );
    }
  	
	public function get_content() {
		global $COURSE, $DB, $PAGE;

  		$this->content = new stdClass;

		// Current page is a student's profile
	  	if(isset($_GET["id"]) && isset($_GET["course"])) {
	  		$user_info = block_get_student_information($_GET["id"]);
			$this->content->text = block_print_user_information($user_info);
	  	}
	  	// Other page
	  	else {
	  		$rankingsize = isset($this->config->rankingsize) ? $this->config->rankingsize : DEFAULT_RANKING_SIZE;
			$users = block_get_students();
	   		if (empty($users)) {
	   			$this->content->text = get_string('nostudents', 'block_iccgb');
	   		} else {
	   			$this->content->text = block_print_ranking($users, $rankingsize);
	   		}

			$pageparam = array('blockid' => $this->instance->id, 
			              'courseid' => $COURSE->id);
	   		$edit_url = new moodle_url('/blocks/iccgb/lrview.php', $pageparam);
	        $edit = html_writer::link($edit_url, html_writer::tag('img', get_string('levels_rewards', 'block_iccgb')));
	        $full_ranking_html = new moodle_url('/blocks/iccgb/frview.php', $pageparam);
	        $full_ranking = html_writer::link($full_ranking_html, html_writer::tag('img', get_string('full_ranking', 'block_iccgb')));

	        $this->content->text .= $edit;
	        $this->content->text .= '<BR>';
	        $this->content->text .= $full_ranking;
   		}
	  	return $this->content;
	}

	public function instance_allow_multiple() {
  		return true;
	}

	public function has_config() {
		return true;
	}

	public function hide_header() {
 		return false;
	}
	
	public function html_attributes() {
	    $attributes = parent::html_attributes(); // Get default values
	    $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
	    return $attributes;
	}

	public function cron() { 
    	return true;
	}
}