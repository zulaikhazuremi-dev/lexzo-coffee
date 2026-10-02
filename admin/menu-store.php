<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";


/*
|--------------------------------------------------------------------------
| Check Request
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: menu-create.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Get Form Data
|--------------------------------------------------------------------------
*/

$categoryId = $_POST["category_id"] ?? "";
$name = trim($_POST["name"] ?? "");
$description = trim($_POST["description"] ?? "");
$price = $_POST["price"] ?? "";
$priceType = $_POST["price_type"] ?? "fixed";
$sortOrder = $_POST["sort_order"] ?? 0;
$status = $_POST["status"] ?? 1;


/*
|--------------------------------------------------------------------------
| Basic Validation
|--------------------------------------------------------------------------
*/

if (
    $categoryId === "" ||
    $name === "" ||
    $price === ""
) {
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
| Check Category
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
| Handle Image Upload
|--------------------------------------------------------------------------
*/

$imagePath = null;

if (isset($_FILES["image"]) && $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        die("Image upload failed.");
    }


    /*
    | Maximum file size: 2MB
    */

    if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
        die("Image must be 2MB or smaller.");
    }


    /*
    | Check MIME type
    */

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

    if (!isset($allowedTypes[$fileType])) {
        die("Only JPG, PNG or WebP images are allowed.");
    }


    /*
    | Create upload directory
    */

    $uploadDirectory = "../frontend/images/menu/";

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }


    /*
    | Generate unique filename
    */

    $extension = $allowedTypes[$fileType];

    $filename = uniqid("menu_", true) . "." . $extension;

    $destination = $uploadDirectory . $filename;


    /*
    | Move uploaded file
    */

    if (!move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $destination
    )) {
        die("Failed to save image.");
    }


    /*
    | Path stored in database
    */

    $imagePath = "images/menu/" . $filename;
}


/*
|--------------------------------------------------------------------------
| Insert Menu
|--------------------------------------------------------------------------
*/

$sql = "
    INSERT INTO menu_items
    (
        category_id,
        name,
        description,
        price,
        price_type,
        image,
        sort_order,
        status
    )
    VALUES
    (
        :category_id,
        :name,
        :description,
        :price,
        :price_type,
        :image,
        :sort_order,
        :status
    )
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
    ":status" => $status
]);


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: menu.php?success=created");
exit;

?>