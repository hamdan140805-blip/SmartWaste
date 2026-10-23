<?php
header("Content-Type: application/json");

require_once __DIR__ . "/db.php";

// Get the current fill levels from the database.
$sql = "SELECT bin_id, fill_level FROM bins";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Could not load bin data"]);
    exit;
}

$bins = [];

while ($row = $result->fetch_assoc()) {
    $bins[] = [
        "bin_id" => (int)$row["bin_id"],
        "fill_level" => (float)$row["fill_level"]
    ];
}

// Send the bin data to the Python prediction script.
$python = "py";
$script = realpath(__DIR__ . "/../ml/predict.py");

if (!$script) {
    http_response_code(500);
    echo json_encode(["error" => "Prediction script not found"]);
    exit;
}

$command = $python . " " . escapeshellarg($script) . " 2>&1";

$process = proc_open(
    $command,
    [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ],
    $pipes
);

if (!is_resource($process)) {
    http_response_code(500);
    echo json_encode(["error" => "Could not start Python"]);
    exit;
}

fwrite($pipes[0], json_encode($bins));
fclose($pipes[0]);

$output = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$error = stream_get_contents($pipes[2]);
fclose($pipes[2]);

$exitCode = proc_close($process);

if ($exitCode !== 0) {
    http_response_code(500);
    echo json_encode([
        "error" => "Python prediction failed",
        "details" => trim($error . "\n" . $output)
    ]);
    exit;
}

// Return the predictions to the dashboard.
echo $output;

$conn->close();
?>