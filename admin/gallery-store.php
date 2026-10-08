<?php

require_once '../backend/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gallery.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$sortOrder = (int) ($_POST['sort_order'] ?? 0);

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die('Please select an image.');
}

$file = $_FILES['image'];

$allowedTypes = [
    'image/jpeg',
    'image/png',
    'image/webp'
];

if (!in_array($file['type'], $allowedTypes)) {
    die('Only JPG, PNG and WebP images are allowed.');
}

if ($file['size'] > 2 * 1024 * 1024) {
    die('Image size must not exceed 2MB.');
}

$uploadDir = '../frontend/images/';

$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

$fileName = 'gallery-' . uniqid() . '.' . strtolower($extension);

$uploadPath = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    die('Failed to upload image.');
}

$imagePath = 'images/' . $fileName;

$stmt = $pdo->prepare("
    INSERT INTO gallery
    (title, image, sort_order, status)
    VALUES
    (?, ?, ?, 1)
");

$stmt->execute([
    $title,
    $imagePath,
    $sortOrder
]);

header('Location: gallery.php');
exit;