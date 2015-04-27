#!/usr/bin/php
<?php

$timer_start = microtime(true);
if (!defined('_PS_ADMIN_DIR_'))
    define('_PS_ADMIN_DIR_', getcwd());

if (!defined('PS_ADMIN_DIR'))
    define('PS_ADMIN_DIR', _PS_ADMIN_DIR_);

require(_PS_ADMIN_DIR_.'/config/config.inc.php');

// For retrocompatibility with "tab" parameter
if (!isset($_GET['controller']) && isset($_GET['tab']))
    $_GET['controller'] = strtolower($_GET['tab']);
if (!isset($_POST['controller']) && isset($_POST['tab']))
    $_POST['controller'] = strtolower($_POST['tab']);
if (!isset($_REQUEST['controller']) && isset($_REQUEST['tab']))
    $_REQUEST['controller'] = strtolower($_REQUEST['tab']);

$_GET['var1'] = $argv[1];

if(isset($argv[2])) {
    $_GET['var2'] = $argv[2];
    if (isset($argv[3])) {
        $_GET['var3'] = $argv[3];
    }
}

error_reporting(0);
Dispatcher::getInstance()->dispatch();




