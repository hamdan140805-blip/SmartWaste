<?php
session_start();

require_once __DIR__ . "/db.php";
require_once __DIR__ . "/access_codes.php";

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";
$role = $_POST["role"] ?? "";
$accessCode = $_POST["access_code"] ?? "";

// Return to the login page with a message.
function showLoginError($message) {
    header("Location: ../frontend/index.html?error=" . $message);
    exit();
}

// Check the login fields.
if ($username === "" || $password === "" || $accessCode === "") {
    showLoginError("login");
}

// Get the access code for the selected role.
if ($role === "user") {
    $correctCode = $userCode;
} elseif ($role === "admin") {
    $correctCode = $adminCode;
} else {
    showLoginError("login");
}

// Check the access code.
if (!hash_equals($correctCode, $accessCode)) {
    showLoginError("access");
}

// Find the account.
$stmt = $conn->prepare(
    "SELECT id, username, password, role
     FROM admins
     WHERE username = ?"
);

$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$account = $result->fetch_assoc();

if ($account) {
    $storedPassword = $account["password"];

    $passwordMatches = password_verify($password, $storedPassword)
        || hash_equals($storedPassword, $password);

    // Check both the password and the saved role.
    if ($passwordMatches && $account["role"] === $role) {
        session_regenerate_id(true);

        $_SESSION["user_id"] = $account["id"];
        $_SESSION["username"] = $account["username"];
        $_SESSION["role"] = $account["role"];

        $stmt->close();
        $conn->close();

        header("Location: ../frontend/dashboard.html");
        exit();
    }
}

$stmt->close();
$conn->close();

showLoginError("login");
?>