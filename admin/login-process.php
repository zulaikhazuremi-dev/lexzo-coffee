<?php

session_start();

require_once "../backend/config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email === "" || $password === "") {
    die("Email and password are required.");
}

$sql = "
    SELECT id, name, email, password, role
    FROM users
    WHERE email = :email
    AND status = 1
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":email" => $email
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user["password"])) {
    die("Invalid email or password.");
}

$_SESSION["admin_id"] = $user["id"];
$_SESSION["admin_name"] = $user["name"];
$_SESSION["admin_email"] = $user["email"];
$_SESSION["admin_role"] = $user["role"];

header("Location: dashboard.php");
exit;

?>