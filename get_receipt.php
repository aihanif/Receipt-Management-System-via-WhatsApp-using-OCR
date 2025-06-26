<?php

// Connect to database
require_once 'connection_work.php';

if (!$conn) {
	throw new Exception("Connection to Database not exist.");
}

$id = (int) $_GET['id'];
$res = $conn->query("SELECT * FROM TBL_Receipt WHERE ID = $id LIMIT 1");
echo json_encode($res->fetch_assoc());

