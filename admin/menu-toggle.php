<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";


/*
|--------------------------------------------------------------------------
| Get Menu ID
|--------------------------------------------------------------------------
*/

$id = $_GET["id"] ?? "";

if (!is_numeric($id)) {
    die("Invalid menu ID.");
}


/*
|--------------------------------------------------------------------------
| Get Current Status
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT id, status
    FROM menu_items
    WHERE id = :id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":id" => $id
]);

$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu item not found.");
}


/*
|--------------------------------------------------------------------------
| Toggle Status
|--------------------------------------------------------------------------
*/

$newStatus = $menu["status"] == 1 ? 0 : 1;

$updateSql = "
    UPDATE menu_items
    SET status = :status
    WHERE id = :id
";

$updateStmt = $pdo->prepare($updateSql);

$updateStmt->execute([
    ":status" => $newStatus,
    ":id" => $id
]);


/*
|--------------------------------------------------------------------------
| Back to Menu
|--------------------------------------------------------------------------
*/

header("Location: menu.php?success=status");
exit;

?>