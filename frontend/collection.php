<?php
include "../backend/db.php";
$result = $conn->query("SELECT * FROM collection_requests");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Requests</title>

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

        .table-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 500px;
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

        .pending {
            font-weight: bold;
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
    </style>
</head>

<body>

    <header class="header">
        <h1>Smart Waste Management System</h1>
    </header>

    <div class="container">
        <h2>Collection Requests</h2>

        <div class="table-box">
            <table>
                <tr>
                    <th>Request ID</th>
                    <th>Bin ID</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["request_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["bin_id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["request_date"]); ?></td>
                    <td class="pending"><?php echo htmlspecialchars($row["status"]); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <a class="back" href="dashboard.html">← Back to Dashboard</a>
    </div>

</body>
</html>