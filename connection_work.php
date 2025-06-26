<?php


// info connection to MySQL
$host = ""; 
$username = "";
$password = "";
$database = "";

// connect to MySQL
$conn = new mysqli($host, $username, $password, $database);


try {
    $conn = new mysqli($host, $username, $password, $database);
    $conn->set_charset("utf8mb4"); 
} catch (Exception $e) {
    http_response_code(500);
    error_log("Database connection error: " . $e->getMessage()); // Log error
    echo json_encode(["error" => "Failed to connect database"]);
    exit;
}


?>