<?php
    $settings->add(new admin_setting_configtext('block_iccgb/max_lvl', get_string('max_lvl', 'block_iccgb'),
        '', 10, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/resourcepoints', get_string('resourcepoints', 'block_iccgb'),
        '', 2, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/assignpoints', get_string('assignpoints', 'block_iccgb'),
        '', 2, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/forumpoints', get_string('forumpoints', 'block_iccgb'),
        '', 2, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/pagepoints', get_string('pagepoints', 'block_iccgb'),
        '', 2, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/workshoppoints', get_string('workshoppoints', 'block_iccgb'),
        '', 2, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/defaultpoints', get_string('defaultpoints', 'block_iccgb'),
        '', 2, PARAM_INT));

    $settings->add(new admin_setting_configtext('block_iccgb/defaultlevelupexp', get_string('defaultlevelupexp', 'block_iccgb'),
        '', 10, PARAM_INT));