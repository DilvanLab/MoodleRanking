<?php
class StudentProfile {
    public $level;
    public $badge;
    public $pic;
    public $name;
    public $points;
    function __construct($l, $b, $p, $n, $pt) {
        $this->level = $l;
        $this->badge = $b;
        $this->pic = $p;
        $this->name = $n;
        $this->points = $pt;
    }
}

// Compare function to sort descendingly by points
function cmp($a, $b)
{   
    if ($a->points == $b->points) {
    return 0;
    }
    return ($a->points < $b->points) ? 1 : -1;
}


// define ('DEFAULT_POINTS', 2);
define ('FIRST_USER', 0);
define ('MAX_LEVEL', 10);
define ('DEFAULT_RANKING_SIZE', 5);

define ('DEFAULT_GRADES_WEIGHT', 1);
define ('DEFAULT_COMPLETION_WEIGHT', 1);
define ('DEFAULT_ACTIVITY_WEIGHT', 2);

/**
 * Returns info about a specific student
 *
 * @return object
 */
function block_get_student_information($id) {
    global $COURSE, $DB, $PAGE;

    $userfields = user_picture::fields('u', array('username'));
    $sql = "SELECT
        DISTINCT $userfields, concat(u.firstname, ' ',u.lastname) as fullname
        FROM mdl_user u
        WHERE u.id = :userid
        ";

    $params['userid'] = $id;

    $users = array_values($DB->get_records_sql($sql, $params));

    return $users[FIRST_USER];
}

/**
 * Builds the table containing user informations
 *
 * @return string
 */
function block_print_user_information($student) {
    global $OUTPUT, $USER, $COURSE, $CFG;

    $table = new html_table();
    $table->attributes = array("class" => "rankingTable table table-striped generaltable");
    // Implement benefits later
    // $table->head = array(
    //                     get_string('table_name', 'block_iccgb'),
    //                     get_string('table_badge', 'block_iccgb'),
    //                     get_string('table_level', 'block_iccgb'),
    //                     get_string('table_benefits', 'block_iccgb')
    //                 );

    $table->head = array(
                    get_string('table_badge', 'block_iccgb'),
                    get_string('table_name', 'block_iccgb'),
                    get_string('table_level', 'block_iccgb'),
                    get_string('table_total_points', 'block_iccgb')
                );

    $row = new html_table_row();

    $user_exp = sum_user_exp($student->id);
    $userlevel = calculate_level($user_exp);
    $picdir = $CFG->wwwroot.'/blocks/iccgb/lvbadges/';
    $picname = 'c'.$COURSE->id.'l'.$userlevel;
    $pic = '<object data="'.$picdir.$picname.'" class="badge_image" type="image/png">
                <img src="'.$picdir.'default'.($userlevel).'.png" class="badge_image"  /></object>';

    $student_profile = new StudentProfile(
                $userlevel,
                $pic,
                $OUTPUT->user_picture($student, array('size' => 24, 'alttext' => false)),
                $student->fullname,
                round($user_exp)
            );

    // Verify if the logged user is one user in ranking.
    if ($student->id == $USER->id) {
        $row->attributes = array('class' => 'itsme');
    }

    // Implement benefits later
    // $row->cells = array(
    //                     $student_profile->pic.$student_profile->name,
    //                     $student_profile->badge,
    //                     $student_profile->level,
    //                     //benefits (to implement)
    //                 );
    
    // Prints badges just in case user has reached the minimum level
    if($student_profile->level != 0) { 
        $row->cells = array(
                            $student_profile->badge,
                            $student_profile->pic.$student_profile->name,
                            $student_profile->level,
                            $student_profile->points
                        );
    }
    else {
        $row->cells = array(
                            '',
                            $student_profile->pic.$student_profile->name,
                            $student_profile->level
                        );
    }
    $table->data[] = $row;

    $standout = block_ranking_calculate_standout($student->fullname);
    $standout_text = get_string('standout_text_1', 'block_iccgb').$standout.get_string('standout_text_2', 'block_iccgb');
    
    $content = html_writer::div($standout_text);
    $content .= '<br>';
    $content .= html_writer::table($table);

    return $content;
}

/**
 * Return within what range of best students the user is or zero if anything went wrong or
 * in case the user is unranked
 *
 * @return array
 */
function block_ranking_calculate_standout($user_name) {
    global $COURSE, $DB;

    $students = block_get_students(MAX_STUDENTS);

    // Create a ladder with users's experience and name and sort it
    for ($i = 0; $i < count($students); $i++) { 
        $user_exp = sum_user_exp($students[$i]->id);
        $fullname = $students[$i]->fullname;
        
        $user_info = new stdClass();
        $user_info->exp = $user_exp;
        $user_info->fullname = $students[$i]->fullname;

        $ladder[] = $user_info;
    }
    rsort($ladder);

    // Find one's position in the ranking
    $cont = 0;
    while($cont < count($ladder) && $ladder[$cont]->fullname != $user_name) {
        $cont++;
    }

    // User not found
    $standout = $cont == count($ladder) ? 0 : (($cont+1) / count($students));

    if($standout == 0) {
        return 0;
    }
    else if($standout < 5) {
        return 5;
    }
    else if($standout < 10) {
        return 10;
    }
    else if($standout < 20) {
        return 20;
    }
    else {
        return 0;
    }
}

/**
 * Return the list of students in the course ranking
 *
 * @return array
 */
function block_get_students() {
    global $COURSE, $DB, $PAGE;

    $context = $PAGE->context;

	$userfields = user_picture::fields('u', array('username'));

	$sql = " SELECT
            DISTINCT $userfields, concat(u.firstname, ' ',u.lastname) as fullname
        FROM
        	mdl_user u
        INNER JOIN mdl_role_assignments a ON a.userid = u.id
        INNER JOIN mdl_context c ON c.id = a.contextid
        WHERE a.contextid = :contextid
        AND a.userid = u.id
        AND a.roleid = :roleid
        AND c.instanceid = :courseid ";
		
	$params['contextid'] = $context->id;
	$params['roleid'] = 5;
	$params['courseid'] = $COURSE->id;

	$users = array_values($DB->get_records_sql($sql, $params));

    return $users;
}

/**
 * Build the ranking table to be viewd in the course
 * @param array $students List the students and their respective levels
 * @return string
 */
function block_print_ranking($students, $rankingsize) {
    global $OUTPUT, $USER, $COURSE, $CFG;

    $table = new html_table();
    $table->attributes = array("class" => "rankingTable table table-striped generaltable");
    
    // Full ranking
    if($rankingsize == count($students)) {
        $table->head = array(
                            get_string('table_position', 'block_iccgb'),
                            get_string('table_name', 'block_iccgb'),
                            get_string('table_badge', 'block_iccgb'),
                            get_string('table_level', 'block_iccgb'),
                            get_string('table_total_points', 'block_iccgb')
                        );
    } 
    // Partial ranking
    else {
        $table->head = array(
                            get_string('table_position', 'block_iccgb'),
                            get_string('table_name', 'block_iccgb'),
                            get_string('table_badge', 'block_iccgb'),
                            get_string('table_level', 'block_iccgb')
                        );
    }


    for ($i = 0; $i < count($students); $i++) { 
        $user_exp = sum_user_exp($students[$i]->id);
        $userlevel = calculate_level($user_exp);

        if($userlevel != 0) { 
            $picdir = $CFG->wwwroot.'/blocks/iccgb/lvbadges/';
            $picname = 'c'.$COURSE->id.'l'.$userlevel;
            $pic = '<object data="'.$picdir.$picname.'" class="badge_image" type="image/png">
                    <img src="'.$picdir.'default'.($userlevel).'.png" class="badge_image"  /></object>';
        }
        else {
            $pic = '';
        }

        $users[] = new StudentProfile(
                $userlevel,
                $pic,
                $OUTPUT->user_picture($students[$i], array('size' => 24, 'alttext' => false)),
                $students[$i]->fullname,
                $user_exp
            );
    }

    // Sorts students in descending order according to their points
    usort($users, "cmp");

    // User's ranking position counter
    $count = 1;
    for($i = 0; $i < min($rankingsize, count($users)); $i++) {
        $row = new html_table_row();

        // Full ranking
        if($rankingsize == count($students)) {
            $row->cells = array(
                            $count,
                            $users[$i]->pic.$users[$i]->name,
                            $users[$i]->badge,
                            $users[$i]->level,
                            round($users[$i]->points)
                        );
        } 
        // Partial ranking
        else {
            $row->cells = array(
                            $count,
                            $users[$i]->pic.$users[$i]->name,
                            $users[$i]->badge,
                            $users[$i]->level
                        );
        }

        $table->data[$i] = $row;

        if($i == count($users) - 1) {
            break;
        }

        // Sets a new ranking position in case the next user has more points
        // or keeps it the same in case of a draw
        if(round($users[$i+1]->points) != round($users[$i]->points)) {
            $count++;
        }
    }

    return html_writer::table($table);
}

/**
 * Returns the modules completions
 *
 * @return integer
 */
function sum_user_exp($user_id) {
    global $DB, $COURSE;

    // Activities completion grade sum approach
    $user_activities = get_completed_activities_by($user_id);
    $sum = 0;

    $coursecontext = context_course::instance($COURSE->id);
    $blockinstance = $DB->get_record('block_instances', array('blockname' => 'iccgb', 'parentcontextid' => $coursecontext->id));
    $iccgb_instance = block_instance('iccgb', $blockinstance);
    $cfg = (array) $iccgb_instance->config;

    $grades_weight = isset($cfg['grades_weight']) ? $cfg['grades_weight'] : DEFAULT_GRADES_WEIGHT;
    $completion_weight = isset($cfg['completion_weight']) ? $cfg['completion_weight'] : DEFAULT_COMPLETION_WEIGHT;
    for($i = 0; $i < count($user_activities); $i++) {
        $activity_type = $user_activities[$i]->module.'points';
        $activity_worth = isset($cfg[$activity_type]) ? $cfg[$activity_type] : DEFAULT_ACTIVITY_WEIGHT;
        $sum += $grades_weight * $user_activities[$i]->finalgrade + $completion_weight * $activity_worth;
    }
    return $sum;
}

/**
 * Returns user's level based on his experience
 *
 * @return integer
 */
function calculate_level($user_exp) {
    global $DB, $COURSE;
    $i = 0;
    $levels_config = (array) $DB->get_record('block_iccgb', array('courseid' => $COURSE->id));
    $level_up_exp = isset ($levels_config['config_iccgb_lvl_1exp']) ? $levels_config['config_iccgb_lvl_1exp'] : get_config('block_iccgb', 'defaultlevelupexp');
    while($user_exp >= $level_up_exp && $i < levels_used($COURSE->id)) {
        $level_up_exp = isset($levels_config['config_iccgb_lvl_'.($i+1).'exp']) ? $levels_config['config_iccgb_lvl_'.($i+1).'exp'] : ($i+1)*get_config('block_iccgb', 'defaultlevelupexp');
        $i++;
    }

    return $i;
}

/**
 * Returns the activities completed by the user
 *
 * @return array
 */
function get_completed_activities_by($user_id) {
    global $DB, $COURSE;
    
    $sql = "SELECT
                gg.id as id,
                gg.finalgrade as finalgrade,
                ci.itemmodule as module,
                ci.iteminstance as instance
            FROM
                {grade_grades} gg
            INNER JOIN {grade_items} ci ON ci.id = gg.itemid
            WHERE
                gg.userid = :userid
                AND ci.itemmodule IS NOT NULL
                AND ci.courseid = :courseid";


    $params['userid'] = $user_id;
    $params['courseid'] = $COURSE->id;
    $completedmodules = array_values($DB->get_records_sql($sql, $params));

    //remove any submissions that were overdue
    foreach($completedmodules as $i=>$v) {
        //change to true to remove the current record
        $remove = false;
        switch($v->module) {
            case 'assign':
                $assignInstance = $DB->get_record("assign", array(
                    "id" => $v->instance
                ));
                if($assignInstance) {
                    $submission = $DB->get_record("assign_submission", array(
                        "assignment" => $assignInstance->id,
                        "userid" => $user_id
                    ));

                    if($submission){
                        $remove = $submission->timemodified > $assignInstance->duedate;
                    }
                }
                break;
        }

        if($remove) {
            unset($completedmodules[$i]);
        }
    }

    return $completedmodules;
}

/**
 * Returns the number of levels used by the course
 *
 * @return integer
 */
function levels_used($courseid) {
    global $DB;

    $course_configs = (array) $DB->get_record('block_iccgb', array('courseid' => $courseid));

    $count = 0;
    do {
        $field = 'config_iccgb_lvl_'.($count+1).'exp';
        $current_level_exp = isset($course_configs[$field]) ? $course_configs[$field] : ($count+1)*get_config('block_iccgb', 'defaultlevelupexp');
        if ($current_level_exp != 0) {
            $count++;
        }
    } while($current_level_exp != 0 && $count < MAX_LEVEL);

    return $count;
}

function objectToArray ($object) {
    if(!is_object($object) && !is_array($object))
        return $object;

    return array_map('objectToArray', (array) $object);
}
