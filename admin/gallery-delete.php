<?php

require_once '../backend/config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: gallery.php');
    exit;
}


// Get gallery data
$stmt = $pdo->prepare("
    SELECT *
    FROM gallery
    WHERE id = ?
");

$stmt->execute([$id]);

$gallery = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gallery) {
    die('Gallery image not found.');
}


// Delete image file
$imagePath = '../frontend/' . $gallery['image'];

if (file_exists($imagePath)) {
    unlink($imagePath);
}


// Delete database record
$stmt = $pdo->prepare("
    DELETE FROM gallery
    WHERE id = ?
");

$stmt->execute([$id]);


header('Location: gallery.php');
exit;