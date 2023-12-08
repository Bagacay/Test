<?php

include_once("./connection.php");

// Function to get the latest location from the database
function getLocationFromDatabase()
{
    $conn = connection();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    try {
        // Retrieve the latest location from the database


        $sql = "SELECT latitude, longitude, timestamp FROM locations ORDER BY timestamp DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row;
        } else {
            return null;
        }
    } catch (Exception $e) {
        return array('error' => $e->getMessage());
    } finally {
        $conn->close();
    }
}

// Get location from the database
$location = getLocationFromDatabase();

// Send response back to the JavaScript code
if ($location === null) {
    echo json_encode(['error' => 'Location not found']);
} else {
    echo json_encode($location);
}
