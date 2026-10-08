<?php

require_once '../backend/config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: gallery.php');
    exit;
}


// Get current status
$stmt = $pdo->prepare("
    SELECT status
    FROM gallery
    WHERE id = ?
");

$stmt->execute([$id]);

$gallery = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gallery) {
    die('Gallery image not found.');
}


// Toggle status
$newStatus = $gallery['status'] == 1 ? 0 : 1;

$stmt = $pdo->prepare("
    UPDATE gallery
    SET status = ?
    WHERE id = ?
");

$stmt->execute([
    $newStatus,
    $id
]);


header('Location: gallery.php');
exit;