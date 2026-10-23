<?php
include '../backend/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM industry_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Company request not found.");
}

function showDate($date) {
    if (empty($date) || $date == '0000-00-00') {
        return "Not available";
    }

    return date("d M Y", strtotime($date));
}

function showTime($time) {
    if (empty($time) || $time == '00:00:00') {
        return "Not available";
    }

    return date("h:i A", strtotime($time));
}

$company = $row['industry_name'] ?? 'Company';
$areas = $row['collection_areas'] ?? 'Not specified';
$priority = strtolower($row['priority'] ?? '');
$quantity = (float)($row['waste_quantity'] ?? 0);
$deadline = $row['requested_deadline'] ?? '';

if ($priority == 'high') {
    $suggestion = "Give priority to waste collection in the specified surrounding areas and check the earliest suitable collection slot.";
} elseif (!empty($deadline) && strtotime($deadline) <= strtotime('+2 days')) {
    $suggestion = "The requested deadline is approaching. Review the specified areas and coordinate collection at the earliest suitable time.";
} elseif ($quantity >= 500) {
    $suggestion = "Plan a suitable vehicle for the expected waste quantity and coordinate collection across the specified surrounding areas.";
} else {
    $suggestion = "Review the specified surrounding areas and coordinate a suitable waste collection schedule with the company.";
}

$message = "Dear " . $company . " Team,\n\n"
    . "Thank you for coordinating with our Smart Waste Management team. "
    . "We would like to coordinate waste collection from the areas surrounding your industry: "
    . $areas . ".\n\n"
    . $suggestion . "\n\n"
    . "Please let us know a suitable time to coordinate the collection.\n\n"
    . "Regards,\nSmart Waste Management Team";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industry Request Details</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        h1, h2 {
            color: #236b43;
        }

        h1 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 11px;
            text-align: left;
            overflow-wrap: anywhere;
        }

        th {
            width: 35%;
            background: #edf5ef;
        }

        textarea {
            width: 100%;
            min-height: 180px;
            padding: 12px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            resize: vertical;
        }

        button, .back-button {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            background: #287747;
            color: white;
            text-decoration: none;
            cursor: pointer;
            margin-top: 10px;
            font-size: 14px;
        }

        button:hover, .back-button:hover {
            background: #1d5b35;
        }

        .suggestion {
            background: #f0f7f1;
            padding: 14px;
            border-left: 4px solid #287747;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Industry Request Details</h1>

    <h2>Company Information</h2>

    <table>
        <tr>
            <th>Company / Industry Name</th>
            <td><?= htmlspecialchars($company) ?></td>
        </tr>
        <tr>
            <th>Contact Person</th>
            <td><?= htmlspecialchars($row['contact_name'] ?? 'Not available') ?></td>
        </tr>
        <tr>
            <th>Designation</th>
            <td><?= htmlspecialchars($row['contact_designation'] ?? 'Not available') ?></td>
        </tr>
        <tr>
            <th>Contact Number</th>
            <td><?= htmlspecialchars($row['contact_phone'] ?? 'Not available') ?></td>
        </tr>
        <tr>
            <th>Request Date</th>
            <td><?= showDate($row['request_date'] ?? '') ?></td>
        </tr>
    </table>

    <h2>Waste Collection Details</h2>

    <table>
        <tr>
            <th>Collection Areas</th>
            <td><?= htmlspecialchars($areas) ?></td>
        </tr>
        <tr>
            <th>Waste Quantity</th>
            <td><?= htmlspecialchars($row['waste_quantity'] ?? 'Not available') ?></td>
        </tr>
        <tr>
            <th>Requested Deadline</th>
            <td><?= showDate($deadline) ?></td>
        </tr>
        <tr>
            <th>Preferred Collection Time</th>
            <td><?= showTime($row['preferred_collection_time'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Scheduled Collection Date</th>
            <td><?= showDate($row['scheduled_collection_date'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Scheduled Collection Time</th>
            <td><?= showTime($row['scheduled_collection_time'] ?? '') ?></td>
        </tr>
        <tr>
            <th>Priority</th>
            <td><?= htmlspecialchars($row['priority'] ?? 'Not available') ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($row['status'] ?? 'Not available') ?></td>
        </tr>
    </table>

    <h2>Suggestion for This Company</h2>

    <div class="suggestion">
        <?= htmlspecialchars($suggestion) ?>
    </div>

    <h2>Message for the Company</h2>

    <textarea id="companyMessage"><?= htmlspecialchars($message) ?></textarea>

    <br>

    <button type="button" onclick="copyMessage()">Copy Message</button>

    <p id="copyStatus"></p>

    <a href="industry_requests.php" class="back-button">Back to Industry Requests</a>

</div>

<script>
function copyMessage() {
    var message = document.getElementById("companyMessage");

    message.select();
    message.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(message.value)
        .then(function () {
            document.getElementById("copyStatus").textContent =
                "Message copied successfully.";
        })
        .catch(function () {
            document.getElementById("copyStatus").textContent =
                "Please select and copy the message manually.";
        });
}
</script>

</body>
</html>