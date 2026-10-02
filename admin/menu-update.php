<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: menu.php");
    exit;
}

$id = $_POST["id"] ?? "";
$categoryId = $_POST["category_id"] ?? "";
$name = trim($_POST["name"] ?? "");
$description = trim($_POST["description"] ?? "");
$price = $_POST["price"] ?? "";
$priceType = $_POST["price_type"] ?? "fixed";
$sortOrder = $_POST["sort_order"] ?? 0;
$status = $_POST["status"] ?? 1;

/*
|--------------------------------------------------------------------------
| Validate basic fields
|--------------------------------------------------------------------------
*/

if (!is_numeric($id) || $categoryId === "" || $name === "" || $price === "") {
    die("Please fill in all required fields.");
}

if (!is_numeric($price) || $price < 0) {
    die("Invalid price.");
}

if (!in_array($priceType, ["fixed", "from"])) {
    die("Invalid price type.");
}

if (!is_numeric($sortOrder) || $sortOrder < 0) {
    die("Invalid sort order.");
}

if (!in_array((int) $status, [0, 1])) {
    die("Invalid status.");
}

/*
|--------------------------------------------------------------------------
| Get current menu item
|--------------------------------------------------------------------------
*/

$menuSql = "
    SELECT id, image
    FROM menu_items
    WHERE id = :id
    LIMIT 1
";

$menuStmt = $pdo->prepare($menuSql);

$menuStmt->execute([
    ":id" => $id
]);

$menu = $menuStmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    die("Menu item not found.");
}

$oldImage = $menu["image"];

/*
|--------------------------------------------------------------------------
| Validate category
|--------------------------------------------------------------------------
*/

$categorySql = "
    SELECT id
    FROM categories
    WHERE id = :category_id
    AND status = 1
    LIMIT 1
";

$categoryStmt = $pdo->prepare($categorySql);

$categoryStmt->execute([
    ":category_id" => $categoryId
]);

$category = $categoryStmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    die("Invalid category.");
}

/*
|--------------------------------------------------------------------------
| Keep existing image by default
|--------------------------------------------------------------------------
*/

$imagePath = $oldImage;

/*
|--------------------------------------------------------------------------
| Upload new image if selected
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES["image"]) &&
    $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        die("Image upload failed.");
    }

    if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
        die("Image must be 2MB or smaller.");
    }

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    $fileType = mime_content_type(
        $_FILES["image"]["tmp_name"]
    );

    if (!isset($allowedTypes[$fileType])) {
        die("Only JPG, PNG or WebP images are allowed.");
    }

    $uploadDirectory = "../frontend/images/menu/";

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $extension = $allowedTypes[$fileType];

    $filename = uniqid("menu_", true) . "." . $extension;

    $destination = $uploadDirectory . $filename;

    if (!move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $destination
    )) {
        die("Failed to save image.");
    }

    $imagePath = "images/menu/" . $filename;
}

/*
|--------------------------------------------------------------------------
| Update menu item
|--------------------------------------------------------------------------
*/

$sql = "
    UPDATE menu_items
    SET
        category_id = :category_id,
        name = :name,
        description = :description,
        price = :price,
        price_type = :price_type,
        image = :image,
        sort_order = :sort_order,
        status = :status
    WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":category_id" => $categoryId,
    ":name" => $name,
    ":description" => $description,
    ":price" => $price,
    ":price_type" => $priceType,
    ":image" => $imagePath,
    ":sort_order" => $sortOrder,
    ":status" => $status,
    ":id" => $id
]);

/*
|--------------------------------------------------------------------------
| Delete old image
|--------------------------------------------------------------------------
|
| Only delete if the old image belongs to our menu image folder.
|
*/

if (
    $imagePath !== $oldImage &&
    !empty($oldImage) &&
    strpos($oldImage, "images/menu/") === 0
) {

    $oldImageFile = "../frontend/" . $oldImage;

    if (file_exists($oldImageFile)) {
        unlink($oldImageFile);
    }
}

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: menu.php?success=updated");
exit;

?>