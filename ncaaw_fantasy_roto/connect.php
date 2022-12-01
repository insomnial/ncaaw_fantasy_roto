<?php
require_once 'local_login.php';

$conn = new mysqli($hostname, $username, $password, $database);
if ($conn->connect_error)
{
    mysql_fatal_error();
    die("Fatal error.");
}
// set time zone to PST
$conn->query("SET time_zone='-08:00'");
?>