<?php
include "../backend/db.php";

$result = $conn->query("SELECT * FROM bins");

$predictions = [
    1 => 43.5,
    2 => 93.5,
    3 => 100
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ML Prediction Report</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f4;
            color: #222;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 22px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 26px;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }

        h2 {
            margin-bottom: 10px;
        }

        .description {
            color: #666;
            margin-bottom: 25px;
        }

        .report-box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        th {
            background-color: #e8e8e8;
            color: #222;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f7f7f7;
        }

        .prediction {
            font-weight: bold;
        }

        .high {
            color: #8a6200;
            font-weight: bold;
        }

        .full {
            color: #b42318;
            font-weight: bold;
        }

        .normal {
            color: #333;
            font-weight: bold;
        }

        .info-box {
            margin-top: 25px;
            padding: 20px;
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .info-box h3 {
            margin-top: 0;
        }

        .back {
            display: inline-block;
            margin-top: 25px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back:hover {
            background-color: #555;
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <h1>Smart Waste Management System</h1>
</header>

<div class="container">

    <h2>ML Prediction Report</h2>

    <p class="description">
        Machine Learning based analysis of waste-bin fill levels.
    </p>

    <div class="report-box">
        <table>
            <tr>
                <th>Bin ID</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Current Fill Level</th>
                <th>Current Status</th>
                <th>Predicted Fill Level</th>
                <th>Recommendation</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) {
                $bin_id = (int)$row["bin_id"];
                $prediction = $predictions[$bin_id] ?? null;
            ?>
            <tr>
                <td>
                    <?php echo "BIN-" . str_pad($bin_id, 3, "0", STR_PAD_LEFT); ?>
                </td>

                <td><?php echo htmlspecialchars($row["location"]); ?></td>
                <td><?php echo htmlspecialchars($row["capacity"]); ?>%</td>
                <td><?php echo htmlspecialchars($row["fill_level"]); ?>%</td>

                <td>
                    <?php
                    $status = $row["status"];
                    if ($status === "Full") {
                        echo '<span class="full">Full</span>';
                    } elseif ($status === "Almost Full") {
                        echo '<span class="high">Almost Full</span>';
                    } else {
                        echo '<span class="normal">' .
                            htmlspecialchars($status) . '</span>';
                    }
                    ?>
                </td>

                <td class="prediction">
                    <?php
                    echo $prediction === null
                        ? "Not available"
                        : htmlspecialchars((string)$prediction) . "%";
                    ?>
                </td>

                <td>
                    <?php
                    if ($prediction === null) {
                        echo "Prediction unavailable";
                    } elseif ($prediction >= 95) {
                        echo '<span class="full">Immediate Collection</span>';
                    } elseif ($prediction >= 80) {
                        echo '<span class="high">Schedule Collection Soon</span>';
                    } else {
                        echo '<span class="normal">Monitor Bin</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php } ?>
        </table>

        <div class="info-box">
            <h3>ML Prediction Summary</h3>
            <p>
                The prototype uses sample fill-level trends to estimate
                next-day levels for the bins with available predictions.
            </p>
            <p>
                Bin 1: <strong>43.5%</strong> |
                Bin 2: <strong>93.5%</strong> |
                Bin 3: <strong>100%</strong>
            </p>
            <p>
                Predictions for other bins are not configured yet.
            </p>
        </div>
    </div>

    <a class="back" href="dashboard.html">← Back to Dashboard</a>

</div>

</body>
</html>