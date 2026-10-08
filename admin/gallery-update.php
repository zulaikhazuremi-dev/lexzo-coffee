<?php

require_once '../backend/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: gallery.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$sortOrder = (int) ($_POST['sort_order'] ?? 0);

if ($id <= 0) {
    die('Invalid gallery ID.');
}


// Get current gallery data
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


// Keep current image by default
$imagePath = $gallery['image'];


// If a new image was uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

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
        die('Failed to upload new image.');
    }

    $imagePath = 'images/' . $fileName;


    // Delete old image
    $oldImage = '../frontend/' . $gallery['image'];

    if (file_exists($oldImage)) {
        unlink($oldImage);
    }
}


// Update database
$stmt = $pdo->prepare("
    UPDATE gallery
    SET
        title = ?,
        image = ?,
        sort_order = ?
    WHERE id = ?
");

$stmt->execute([
    $title,
    $imagePath,
    $sortOrder,
    $id
]);


header('Location: gallery.php');
exit;