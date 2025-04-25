<?php
header("Content-Type:text/html; charset=utf-8");
$serverName = "sqlserver\SQLEXPRESS, 1433";
$connectionInfo = array(
    "Database" => "northwind",
    "UID" => "sa",
    "PWD" => "I!am@password",
    "CharacterSet" => "UTF-8",
    "Encrypt" => "true",
    "TrustServerCertificate" => "true"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn) {
    echo "連線成功!!!<br />";
} else {
    echo "錯誤!!!<br />";
    die(print_r(sqlsrv_errors(), true));
}
//print status
print ("$conn <br>");
sqlsrv_close($conn);
?>