<?php
require_once __DIR__ . "/../backend/db.php";

$sql = "SELECT * FROM industry_requests
        ORDER BY scheduled_collection_date, scheduled_collection_time
        LIMIT 10";

$result = $conn->query($sql);

function clean($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function showDate($date) {
    if (empty($date) || $date == '0000-00-00') {
        return "Not available";
    }

    $time = strtotime($date);
    return $time ? date("d M Y", $time) : "Not available";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industry Requests - SmartWaste</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #303a32;
            background: #fff;
        }

        header {
            padding: 17px 25px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
            font-weight: bold;
            color: #284b35;
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            background: #31543d;
        }

        nav a {
            padding: 13px 16px;
            color: white;
            text-decoration: none;
            font-size: 13px;
        }

        nav a:hover,
        nav a.active {
            background: #42674e;
        }

        main {
            padding: 25px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 25px;
        }

        .intro {
            margin: 0 0 20px;
            color: #727972;
            font-size: 13px;
            line-height: 1.6;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            padding: 12px;
            text-align: left;
            background: #edf2ed;
            color: #405044;
            border-bottom: 1px solid #d5ddd5;
            white-space: nowrap;
        }

        td {
            padding: 13px 12px;
            border-bottom: 1px solid #e4e8e4;
            line-height: 1.5;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f8faf8;
        }

        .industry {
            font-weight: bold;
            color: #31543d;
        }

        .view-btn {
            display: inline-block;
            padding: 7px 14px;
            background: #31543d;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }

        .view-btn:hover {
            background: #42674e;
        }

        .note {
            margin-top: 18px;
            color: #777;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            main {
                padding: 18px 12px;
            }

            nav a {
                padding: 11px 10px;
            }
        }
    </style>
</head>

<body>

<header>Smart Waste Management System</header>

<nav>
    <a href="dashboard.html">Dashboard</a>
    <a class="active" href="industry_requests.php">Industry Requests</a>
    <a href="collection.php">Collection</a>
    <a href="reports.php">Reports</a>
    <a href="prediction.php">ML Prediction</a>
    <a href="search.php">Search</a>
    <a href="bins.php">All Bins</a>
</nav>

<main>
    <h1>Industry Requests</h1>

    <p class="intro">
        View industry collection requests and open any request
        to see its details and collection recommendations.
    </p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Company / Industry</th>
                    <th>Request Date</th>
                    <th>Collection Date</th>
                    <th>Collection Areas</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="industry">
                                <?= clean($row['industry_name']) ?>
                            </td>

                            <td>
                                <?= clean(showDate($row['request_date'])) ?>
                            </td>

                            <td>
                                <?= clean(showDate($row['scheduled_collection_date'])) ?>
                            </td>

                            <td>
                                <?= clean($row['collection_areas']) ?>
                            </td>

                            <td>
                                <a class="view-btn"
                                   href="industry_request_view.php?id=<?= (int)$row['id'] ?>">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="5">No industry requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="note">
        Collection schedules should be confirmed by the administrator.
    </p>
</main>

</body>
</html>

<?php
$conn->close();
?>