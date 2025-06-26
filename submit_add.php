<?php
// submit_add.php

// Connect to Database
require_once 'connection_work.php';

if (!$conn) {
	throw new Exception("Connection to Database not exist.");
}

$data = $_POST;
$required = ['Header', 'Body', 'Create_datetime', 'Address', 'Item', 'Quantity', 'Price', 'Tax'];
foreach ($required as $field) {
  if (!isset($data[$field])) {
    echo json_encode(["status" => "error", "message" => "Missing field: $field"]);
    exit;
  }
}

$stmt = $conn->prepare("INSERT INTO TBL_Receipt (Header, Body, Create_datetime, Address, Item, Quantity, Price, Tax) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssiid",
  $data['Header'],
  $data['Body'],
  $data['Create_datetime'],
  $data['Address'],
  $data['Item'],
  $data['Quantity'],
  $data['Price'],
  $data['Tax']
);

if ($stmt->execute()) {
  echo json_encode(["status" => "success"]);
} else {
  echo json_encode(["status" => "error", "message" => $stmt->error]);
}
$stmt->close();
$conn->close();
