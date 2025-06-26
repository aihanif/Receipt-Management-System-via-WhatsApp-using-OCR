<?php

header("Content-Type: application/json");

// Connect to Database
require_once 'connection_work.php';


$input = file_get_contents("php://input");


if (!$conn) {
	throw new Exception("Connection to Database not exist.");
}

try {

	
	// Read JSON Input
	$input = file_get_contents("php://input");
	$data = json_decode($input, true);

	// Validation
	// Field dan Data Type needed
	$requiredFields = [
		'Header' => 'string',
		'Body' => 'string',
		'Create_datetime' => 'string',
		'Address' => 'string',
		'Item' => 'string',
		'Quantity' => 'int',
		'Price' => 'float',
		'Tax' => 'float'
	];

	//set default Value
	foreach ($requiredFields as $field => $type) {
		if (!isset($data[$field])) {
			switch ($type) {
				case 'string':
					$data[$field] = '';
					break;
				case 'int':
					$data[$field] = 0;
					break;
				case 'float':
					$data[$field] = 0.0;
					break;
			}
		}
	}

	// Sanitize data (avoid SQL injection)
	$header = mysqli_real_escape_string($conn, $data['Header']);
	$body = mysqli_real_escape_string($conn, $data['Body']);
	$datetime = mysqli_real_escape_string($conn, $data['Create_datetime']);
	$address = mysqli_real_escape_string($conn, $data['Address']);
	$item = mysqli_real_escape_string($conn, $data['Item']);
	$quantity = intval($data['Quantity']);
	$price = floatval($data['Price']);
	$tax = floatval($data['Tax']);

	// Query SQL
	$sql = "INSERT INTO TBL_Receipt(Header, Body, Create_datetime, Address, Item, Quantity, Price, Tax) VALUES('$header','$body','$datetime', '$address', '$item', $quantity, $price, $tax)";

	// Execute query
	if (mysqli_query($conn, $sql)) {
		echo json_encode(["status" => "success", "insert_id" => mysqli_insert_id($conn)]);
	} else {
		echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
	}

	mysqli_close($conn);
	

} catch (Exception $e) {
    http_response_code(500);
    error_log("Query error: " . $e->getMessage()); // Log error for debugging
    echo json_encode(["error" => "Failed to fetch data"]);
	mysqli_close($conn);
}	


?>