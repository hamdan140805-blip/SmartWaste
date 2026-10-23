<?php
include "../backend/db.php";

$search = "";
$result = null;

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);

    $stmt = $conn->prepare(
        "SELECT * FROM bins
         WHERE CAST(bin_id AS CHAR) LIKE ?
         OR location LIKE ?
         ORDER BY bin_id ASC"
    );

    $term = "%" . $search . "%";
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Search Bins</title>

<style>
* { box-sizing: border-box; }

body {
    margin: 0;
    background: #eeeeF3;
    color: #222;
    font-family: Arial, sans-serif;
}

header {
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

.search-box,
.table-box {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 20px;
    margin-bottom: 20px;
}

input {
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 70%;
    max-width: 400px;
}

button {
    padding: 11px 18px;
    background: #303044;
    color: white;
    border: 0;
    border-radius: 5px;
    cursor: pointer;
}

.table-box {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 550px;
}

th, td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

th {
    background: #e8e8ed;
}

tr:hover {
    background: #f7f7f9;
}

.message {
    color: #666;
}

@media (max-width: 600px) {
    .sidebar { width: 115px; }
    .sidebar a { padding: 12px 8px; font-size: 12px; }
    main { padding: 12px; }
    input { width: 100%; max-width: none; margin-bottom: 10px; }
}
</style>
</head>

<body>

<header>Smart Waste Management System</header>

<div class="layout">

<nav class="sidebar">
    <a href="dashboard.html">Dashboard</a>
    <a href="collection.php">Collection</a>
    <a href="reports.php">Reports</a>
    <a class="active" href="search.php">Search</a>
    <a href="bins.php">All Bins</a>
    <a href="prediction.php">ML Prediction</a>
    <a href="index.html"
       onclick="return confirm('Return to the login page?')">Logout</a>
</nav>

<main>
    <h2>Search Bins</h2>

    <form class="search-box" method="GET" action="search.php">
        <input
            type="text"
            name="search"
            placeholder="Search by bin ID or location"
            value="<?php echo htmlspecialchars($search); ?>"
            required
        >
        <button type="submit">Search</button>
    </form>

    <?php if ($result !== null) { ?>
    <div class="table-box">
        <table>
            <tr>
                <th>Bin ID</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Fill Level</th>
                <th>Status</th>
            </tr>

            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <?php echo "BIN-" .
                            str_pad($row["bin_id"], 3, "0", STR_PAD_LEFT); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row["location"]); ?></td>
                    <td><?php echo htmlspecialchars($row["capacity"]); ?>%</td>
                    <td><?php echo htmlspecialchars($row["fill_level"]); ?>%</td>
                    <td><?php echo htmlspecialchars($row["status"]); ?></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" class="message">No matching bins found.</td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php } ?>
</main>

</div>
</body>
</html>