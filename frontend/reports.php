<?php
include "../backend/db.php";

$total = $conn->query("SELECT COUNT(*) AS total FROM bins")->fetch_assoc()["total"];

$normal = $conn->query("SELECT COUNT(*) AS total FROM bins WHERE fill_level < 75")->fetch_assoc()["total"];

$almostFull = $conn->query("SELECT COUNT(*) AS total FROM bins WHERE fill_level >= 75 AND fill_level < 100")->fetch_assoc()["total"];

$full = $conn->query("SELECT COUNT(*) AS total FROM bins WHERE fill_level >= 100")->fetch_assoc()["total"];

$pending = $conn->query("SELECT COUNT(*) AS total FROM collection_requests WHERE status = 'Pending'")->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>

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
            max-width: 1000px;
            margin: 35px auto;
            padding: 0 20px;
        }

        h2 {
            margin-bottom: 25px;
        }

        .report-box {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 25px;
        }

        .card h3 {
            color: #444;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            color: #222;
        }

        .pending {
            color: #555;
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
            .report-box {
                grid-template-columns: 1fr;
            }

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

        <h2>Waste Management Reports</h2>

        <div class="report-box">

            <div class="card">
                <h3>Total Bins</h3>
                <div class="number"><?php echo $total; ?></div>
            </div>

            <div class="card">
                <h3>Normal Bins</h3>
                <div class="number"><?php echo $normal; ?></div>
            </div>

            <div class="card">
                <h3>Almost Full</h3>
                <div class="number"><?php echo $almostFull; ?></div>
            </div>

            <div class="card">
                <h3>Full Bins</h3>
                <div class="number"><?php echo $full; ?></div>
            </div>

            <div class="card">
                <h3>Pending Requests</h3>
                <div class="number pending"><?php echo $pending; ?></div>
            </div>

        </div>

        <a class="back" href="dashboard.html">← Back to Dashboard</a>

    </div>

</body>
</html>