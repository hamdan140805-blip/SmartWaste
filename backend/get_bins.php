<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

$result = $conn->query("SELECT * FROM bins ORDER BY bin_id ASC");

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch bins"]);
    exit;
}

$bins = [];

while ($row = $result->fetch_assoc()) {
    $bins[] = $row;
}

echo json_encode($bins);
?>