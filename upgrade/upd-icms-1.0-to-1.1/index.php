<?php

class upgrade_impcms06 {
	
	var $usedFiles = array ();
    var $tasks = array('conf', 'dbversion', 'db');
	var $updater;
	
	function __construct() {
		$this->updater = XoopsDatabaseFactory::getDatabaseUpdater();
	}

    function isApplied()
    {
        if (!isset($_SESSION[__CLASS__]) || !is_array($_SESSION[__CLASS__])) {
            $_SESSION[__CLASS__] = array();
        }
        foreach ($this->tasks as $task) {
            if (!in_array($task, $_SESSION[__CLASS__])) {
                if (!$res = $this->{"check_{$task}"}()) {
                    $_SESSION[__CLASS__][] = $task;
                }
            }
        }
        return empty($_SESSION[__CLASS__]) ? true : false;
    }
    function apply()
    {
        $tasks = $_SESSION[__CLASS__];
        foreach ($tasks as $task) {
            $res = $this->{"apply_{$task}"}();
            if (!$res) return false;
            array_shift($_SESSION[__CLASS__]);
        }
        return true;
    }
    function check_db()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

    function apply_db()
    {
        return $this->update_configs('db');
    }

    function update_configs($task){
	$db = $GLOBALS['xoopsDB'];
        if (!$vars = $this->set_configs($task) ) {
            return false;
        }
        if ($task == "db" && !empty($vars["XOOPS_DB_COLLATION"]) && $pos = strpos($vars["XOOPS_DB_COLLATION"], "_")) {
            $vars["XOOPS_DB_CHARSET"] = substr($vars["XOOPS_DB_COLLATION"], 0, $pos);
            
		    $sql = "ALTER DATABASE `" . XOOPS_DB_NAME . "` DEFAULT CHARACTER SET " . $db->quote($vars['XOOPS_DB_CHARSET']) . " COLLATE " . $db->quote($vars['XOOPS_DB_COLLATION']);
		    if ( !$db->queryF($sql) ) {
    		    return false;
		    }
		    if ( !$result = $db->queryF("SHOW TABLES") ) {
    		    return false;
		    }
		    while (list($table) = $db->fetchRow($result)) {
    		    $db->queryF( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET " . $db->quote($vars['XOOPS_DB_CHARSET']) . " COLLATE " . $db->quote($vars['XOOPS_DB_COLLATION']) );
    		    $db->queryF( "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET " . $db->quote($vars['XOOPS_DB_CHARSET']) . " COLLATE " . $db->quote($vars['XOOPS_DB_COLLATION']) );
		    }
        }
        
        return true;
    }
    function check_conf()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

 	function apply_conf() {
		$db = $GLOBALS['xoopsDB'];
		if (getDbValue($db,'configcategory','confcat_id',' confcat_name="_MD_AM_CONTMANAGER"') != 0){return true;}
		$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('9','_MD_AM_CONTMANAGER')");
		if (getDbValue($db,'configcategory','confcat_id',' confcat_name="_MD_AM_PERSON"') != 0){return true;}
		$db->queryF(" INSERT INTO " . $db->prefix("configcategory") . " (confcat_id,confcat_name) VALUES ('10','_MD_AM_PERSON')");
        
        $passwordmeter_installed = false;
        $sql = "SELECT COUNT(*) FROM `" . $GLOBALS['xoopsDB']->prefix('config') . "` WHERE `conf_name` = 'pass_level'";
        if ( $result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
            list($count) = $GLOBALS['xoopsDB']->fetchRow($result);
            if ($count == 1) {
                $passwordmeter_installed = true;
            }
        }
        if (!$passwordmeter_installed) {
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('config') . 
                    " (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) " .
                    " VALUES " .
                    " (NULL, 0, 2, 'pass_level', '_MD_AM_PASSLEVEL', '20', '_MD_AM_PASSLEVEL_DESC', 'select', 'int', 2)";

            if (!$GLOBALS['xoopsDB']->queryF( $sql )) {
                return false;
            }
            $config_id = $GLOBALS['xoopsDB']->getInsertId();
            
            $sql = "INSERT INTO " . $GLOBALS['xoopsDB']->prefix('configoption') . 
                    " (confop_id, confop_name, confop_value, conf_id)" .
                    " VALUES" .
                    " (NULL, '_MD_AM_PASSLEVEL1', '20', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL2', '40', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL3', '60', {$config_id})," .
                    " (NULL, '_MD_AM_PASSLEVEL4', '80', {$config_id})";
                    " (NULL, '_MD_AM_PASSLEVEL5', '95', {$config_id})";
            if ( !$result = $GLOBALS['xoopsDB']->queryF( $sql ) ) {
                return false;
            }
        }
        
        return $result;

	}
       function check_dbversion()
    {
		$table = new IcmsDatabasetable('modules');
	    return $table->fieldExists('dbversion');
            }

    	function apply_dbversion() {
    	// First let's create the dbversion field in the modules table
    	$table = new IcmsDatabasetable('modules');
    	$table->addNewField('dbversion', 'INT(11) DEFAULT 0');
    	return $this->updater->updateTable($table, true);
	}

    function set_configs($task)
    {
        $ret = array();
        $configs = include dirname(__FILE__) . "/settings_{$task}.php";
        if ( !$configs || !is_array($configs) ) {
            return $ret;
        }
        if (empty($_POST['action']) || $_POST['task'] != $task) {
            return false;
        }
        
        foreach ( $configs as $key => $val ) {
            $ret['XOOPS_' . $key] = $val;
        }
        return $ret;
        
    }
}

$upg = new upgrade_impcms06();
return $upg;
?>