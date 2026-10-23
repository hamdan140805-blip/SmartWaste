<?php
header("Content-Type: application/json");

require_once "db.php";

$sql = "SELECT industry_name, collection_areas, waste_quantity,
               contact_name, requested_deadline,
               scheduled_collection_date, scheduled_collection_time,
               priority, status
        FROM industry_requests
        ORDER BY scheduled_collection_date, scheduled_collection_time";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Could not load industry requests"]);
    exit;
}

$requests = [];

while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}

echo json_encode($requests);

$conn->close();
?>