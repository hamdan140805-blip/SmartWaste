<?php

include __DIR__ . "/db.php";

$total = $conn->query("SELECT COUNT(*) AS total FROM bins")->fetch_assoc()["total"];

$normal = $conn->query("SELECT COUNT(*) AS total FROM bins WHERE status='Normal'")->fetch_assoc()["total"];

$almostFull = $conn->query("SELECT COUNT(*) AS total FROM bins WHERE status='Almost Full'")->fetch_assoc()["total"];

$full = $conn->query("SELECT COUNT(*) AS total FROM bins WHERE status='Full'")->fetch_assoc()["total"];

$pending = $conn->query("SELECT COUNT(*) AS total FROM collection_requests WHERE status='Pending'")->fetch_assoc()["total"];

echo "Total Bins: " . $total . "<br>";
echo "Normal Bins: " . $normal . "<br>";
echo "Almost Full Bins: " . $almostFull . "<br>";
echo "Full Bins: " . $full . "<br>";
echo "Pending Collection Requests: " . $pending . "<br>";

?>