<?php
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: '3306';

error_log("[DEBUG] MYSQLHOST = " . var_export($host, true));
error_log("[DEBUG] MYSQLUSER = " . var_export($user, true));
error_log("[DEBUG] MYSQLDATABASE = " . var_export($db, true));
error_log("[DEBUG] MYSQLPORT = " . var_export($port, true));
error_log("[DEBUG] MYSQLPASSWORD is set = " . var_export(!empty($pass), true));

if ($host) {
    $conn = new mysqli($host, $user, $pass, $db, $port);
} else {
    $conn = new mysqli("127.0.0.1", "root", "", "fivem_family");
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
