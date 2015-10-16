<?php

function xmldb_block_iccgb_upgrade($oldversion = 0) {
    global $DB;
    $dbman = $DB->get_manager();

    $result = true;

    if ($oldversion < 2015022000) {

        // Define table block_iccgb to be created.
    	$table = new xmldb_table('block_iccgb');

        // Adding fields to table block_iccgb.
    	$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    	$table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_1exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_2exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_3exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_4exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_5exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_6exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_7exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_8exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_9exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
    	$table->add_field('config_iccgb_lvl_10exp', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table block_iccgb.
    	$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_iccgb.
    	if (!$dbman->table_exists($table)) {
    		$dbman->create_table($table);
    	}

        // Iccgb savepoint reached.
    	upgrade_block_savepoint(true, 2015022000, 'iccgb');
    }

    return $result;
}

?>