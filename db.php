<?php

// MySQL database parameters
$db_host = '';
$db_user = '';
$db_password = '';
$db_database = '';

// Connect
$db = new PDO("mysql:host=$db_host;dbname=$db_database", $db_user, $db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

?>
