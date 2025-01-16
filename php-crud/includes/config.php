<?php
define('DB_SERVER', '127.0.0.1');  // Changed from localhost to 127.0.0.1
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'crud_db');

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
