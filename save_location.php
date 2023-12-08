<?php
// Replace with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "location";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve latitude and longitude from the GET parameters
$latitude = $_GET['lat'];
$longitude = $_GET['lng'];

// Insert the location data into the database
$sql = "INSERT INTO locations (latitude, longitude) VALUES ('$latitude', '$longitude')";

if ($conn->query($sql) === TRUE) {
    echo "Location saved successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
