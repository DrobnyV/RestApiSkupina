<?php
$host = 'sql.daniellinda.net'; // Your MySQL host
$username = 'remote';          // Your MySQL username
$password = 'hm3C4iLL+';       // Your MySQL password
$database = 'crm_dev';         // Your MySQL database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
