<?php

include __DIR__ . "/db.php";

$sql = "SELECT * FROM collection_requests";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row["request_id"] . " | ";
    echo $row["bin_id"] . " | ";
    echo $row["request_date"] . " | ";
    echo $row["status"] . "<br>";
}

?>