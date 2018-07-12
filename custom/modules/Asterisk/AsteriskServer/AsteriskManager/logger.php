<?php
include('log4php/Logger.php');	
include('config.xml');

function output($type,$message) {
 // Tell log4php to use our configuration file.
 Logger::configure('config.xml');
 // Fetch a logger, it will inherit settings from the root logger
 $log = Logger::getLogger('PHPLogger');
 $log->$type($message);
}
?>
