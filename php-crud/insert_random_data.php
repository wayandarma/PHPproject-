<?php
// Include config file
require_once "includes/config.php";

// Arrays of sample data
$firstNames = ["John", "Jane", "Michael", "Sarah", "David", "Lisa", "Robert", "Emma", "William", "Olivia", 
               "James", "Sophia", "Daniel", "Ava", "Matthew", "Isabella", "Joseph", "Mia", "Christopher", "Charlotte"];

$lastNames = ["Smith", "Johnson", "Williams", "Brown", "Jones", "Garcia", "Miller", "Davis", "Rodriguez", "Martinez",
              "Hernandez", "Lopez", "Gonzalez", "Wilson", "Anderson", "Thomas", "Taylor", "Moore", "Jackson", "Martin"];

$streets = ["Main Street", "Oak Avenue", "Maple Road", "Cedar Lane", "Pine Street", "Elm Court", "Washington Avenue",
            "Park Road", "Lake Drive", "River Street", "Mountain View", "Valley Road", "Forest Lane", "Sunset Boulevard",
            "Highland Avenue"];

$cities = ["New York", "Los Angeles", "Chicago", "Houston", "Phoenix", "Philadelphia", "San Antonio", "San Diego",
           "Dallas", "San Jose", "Austin", "Jacksonville", "Fort Worth", "Columbus", "San Francisco"];

$states = ["NY", "CA", "IL", "TX", "AZ", "PA", "FL", "OH", "MI", "GA", "NC", "WA", "MA", "CO", "MD"];

// Function to generate random address
function generateAddress($streets, $cities, $states) {
    $streetNumber = rand(100, 9999);
    $street = $streets[array_rand($streets)];
    $city = $cities[array_rand($cities)];
    $state = $states[array_rand($states)];
    $zipCode = sprintf("%05d", rand(10000, 99999));
    
    return "$streetNumber $street, $city, $state $zipCode";
}

// Prepare insert statement
$sql = "INSERT INTO employees (name, address, salary) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// Counter for successful inserts
$successCount = 0;

// Insert 50 random records
for ($i = 0; $i < 50; $i++) {
    // Generate random employee data
    $firstName = $firstNames[array_rand($firstNames)];
    $lastName = $lastNames[array_rand($lastNames)];
    $name = "$firstName $lastName";
    $address = generateAddress($streets, $cities, $states);
    $salary = rand(30000, 120000); // Random salary between 30k and 120k

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "sss", $name, $address, $salary);
    
    if (mysqli_stmt_execute($stmt)) {
        $successCount++;
    }
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Output results
echo "<h2>Data Insertion Complete</h2>";
echo "<p>Successfully inserted $successCount records.</p>";
echo "<p><a href='index.php'>Return to Employee List</a></p>";
?>
