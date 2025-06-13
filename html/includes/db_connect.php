<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type:text/html; charset=utf-8");

$serverName = "sqlserver";
$connectionInfo = array(
    "Database" => "OnlineBookstoreDB",
    "UID" => "sa",
    "PWD" => "I!am@password",
    "CharacterSet" => "UTF-8",
    "Encrypt" => true,
    "TrustServerCertificate" => true
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>