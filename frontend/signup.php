<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

require_once __DIR__ . "/../backend/db.php";

$name = trim($_POST["name"] ?? "");
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$confirm = $_POST["confirm_password"] ?? "";

$error = "";

if ($name === "" || $username === "" || $password === "" || $confirm === "") {
    $error = "Please fill in all fields.";
} elseif (!preg_match("/^[A-Z][a-z]+( [A-Z][a-z]+)+$/", $name)) {
    $error = "Please enter a valid full name.";
} elseif (!preg_match("/^[A-Za-z][A-Za-z0-9_]{3,19}$/", $username)) {
    $error = "Please enter a valid username.";
} elseif (strlen($password) < 8) {
    $error = "Password must contain at least 8 characters.";
} elseif ($password !== $confirm) {
    $error = "Passwords do not match.";
}

if ($error === "") {
    $check = $conn->prepare(
        "SELECT id FROM admins WHERE username = ?"
    );

    $check->bind_param("s", $username);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $error = "Username already exists. Please choose another.";
    }

    $check->close();
}

if ($error === "") {
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO admins (username, password, role)
         VALUES (?, ?, 'user')"
    );

    $stmt->bind_param("ss", $username, $hash);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        header("Location: index.html?signup=success");
        exit;
    } else {
        $error = "Could not create account. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Error</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f4f6f4;
            color: #303830;
        }

        .message-box {
            width: 100%;
            max-width: 400px;
            padding: 28px;
            background: white;
            border: 1px solid #e0e5e0;
            border-radius: 8px;
            text-align: center;
        }

        h2 {
            margin-top: 0;
            color: #a32121;
            font-size: 23px;
        }

        p {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            margin-top: 12px;
            padding: 11px 18px;
            background: #286b43;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        a:hover {
            background: #205536;
        }
    </style>
</head>

<body>

    <div class="message-box">
        <h2>Account Not Created</h2>

        <p>
            <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
        </p>

        <a href="index.html">Return to Login</a>
    </div>

</body>
</html>