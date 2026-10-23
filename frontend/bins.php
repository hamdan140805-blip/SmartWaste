<?php
include "../backend/db.php";
$result = $conn->query("SELECT * FROM bins ORDER BY bin_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bin Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eeeeF3;
            color: #222;
        }

        .header {
            height: 45px;
            background: #fff;
            text-align: center;
            padding: 12px;
            font-weight: bold;
            border-bottom: 1px solid #ddd;
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 45px);
        }

        .sidebar {
            width: 180px;
            flex-shrink: 0;
            background: #101018;
            color: #fff;
            padding: 18px 0;
        }

        .sidebar a {
            display: block;
            padding: 13px 18px;
            color: #eee;
            text-decoration: none;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #303044;
        }

        main {
            flex: 1;
            min-width: 0;
            padding: 24px;
        }

        h2 {
            font-size: 22px;
            margin: 0 0 20px;
        }

        .table-box {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 550px;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: #e8e8e8;
            color: #222;
            padding: 13px;
            text-align: left;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background: #f7f7f7;
        }

        .message {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        @media (max-width: 600px) {
            .sidebar {
                width: 125px;
            }

            .sidebar a {
                padding: 12px 8px;
                font-size: 12px;
            }

            main {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<header class="header">
    Smart Waste Management System
</header>

<div class="layout">

    <nav class="sidebar">
        <a href="dashboard.html">Dashboard</a>
        <a href="collection.php">Collection</a>
        <a href="reports.php">Reports</a>
        <a href="prediction.php">ML Prediction</a>
        <a href="search.php">Search</a>
        <a class="active" href="bins.php">All Bins</a>
        <a href="index.html"
           onclick="return confirm('Return to the login page?')">Logout</a>
    </nav>

    <main>
        <h2>Bin Management</h2>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>Bin ID</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Fill Level</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result && $result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php echo "BIN-" . str_pad(
                                $row["bin_id"], 3, "0", STR_PAD_LEFT
                            ); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row["location"] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($row["capacity"] ?? ""); ?>%</td>
                        <td><?php echo htmlspecialchars($row["fill_level"] ?? ""); ?>%</td>
                        <td><?php echo htmlspecialchars($row["status"] ?? ""); ?></td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">No bin records found.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</div>

</body>
</html>