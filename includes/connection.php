<?php
require_once("constants.php");
$dsn = "mysql:dbname=delicion_development;host=127.0.0.1";

try {
    $conn = new PDO($dsn, MYSQLUSER, MYSQLPASS);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

?>
