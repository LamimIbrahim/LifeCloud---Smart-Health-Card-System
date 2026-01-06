<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "lifecloud_db";

// Connection তৈরি করা
$conn = new mysqli($servername, $username, $password, $dbname);

// Connection চেক করা
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
