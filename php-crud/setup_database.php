<?php
$conn = mysqli_connect('127.0.0.1', 'root', '');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS crud_db";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select the database
mysqli_select_db($conn, 'crud_db');

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS employees (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    salary DECIMAL(10,2) NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table created successfully or already exists<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

echo "<p>Setup complete. Now you can run <a href='insert_random_data.php'>insert_random_data.php</a></p>";
?>
