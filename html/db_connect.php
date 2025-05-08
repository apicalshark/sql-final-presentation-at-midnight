<?php
header("Content-Type:text/html; charset=utf-8");
$serverName = "sqlserver\SQLEXPRESS, 1433";
$connectionInfo = array(
    "Database" => "northwind",
    "UID" => "thedbuser",
    "PWD" => "I!am@password",
    "CharacterSet" => "UTF-8",
    "Encrypt" => "true",
    "TrustServerCertificate" => "true"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn) {
    echo "Success!!!<br />";
} else {
    echo "Error!!!<br />";
    die(print_r(sqlsrv_errors(), true));
}
?>