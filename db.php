<?php
$ini_array = parse_ini_file("config.ini");
$db = $ini_array['db'];
$host = $ini_array['host'];
$user = $ini_array['user'];
$pass = $ini_array['pass'];
$pdo = new PDO("mysql:host=$host;dbname=$db", "$user", "$pass");
?>
