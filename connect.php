<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'atn-store';

// Connect to MySQL
$conn = mysqli_connect($hostname, $username, $password, $database);
// Check connection
if (!$conn) {
    die('Failed to connect MySQL: ' . mysqli_connect_error());
}
// echo 'Connect successfully MySQL';
echo "<script>console.log('Debug Objects: Connect successfully MySQL' );</script>";
