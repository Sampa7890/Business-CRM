<?php
// $hosts = [
//     ["host" => "localhost", "port" => 3306],
//     ["host" => "localhost", "port" => 3307],
// ];
// $user = "root";
// $passwords = ["", "Sushweta@(6996)"];
// $db   = "business_crm";

// $conn = false;
// $last_error = "";
// foreach ($hosts as $server) {
//     foreach ($passwords as $pass) {
//         $conn = @mysqli_connect($server["host"], $user, $pass, $db, $server["port"]);
//         if ($conn) {
//             break 2;
//         }
//         $last_error = mysqli_connect_error();
//     }
// }

// if (!$conn) {
//     die("Connection failed: " . $last_error);
// }

$host = "localhost";
$user = "root";
$pass = "";
$db   = "business_crm";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
