<?php 
@session_start();
error_reporting(E_ALL);
require_once('constants.php');
ini_set('display_errors','0');
ini_set('log_errors', '0');
include_once 'classes/cls.common.php';
$Cobj=new common($db);
?>