<?php

// MySQL database parameters
$db_host = '';
$db_user = '';
$db_password = '';
$db_database = '';

// Connect
$db = new PDO('mysql:host='.$db_host.';dbname='.$db_database, $db_user, $db_password);

?>
