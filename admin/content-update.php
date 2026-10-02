<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: content.php");
    exit;
}

$id = $_POST["id"] ?? "";

$title = trim($_POST["title"] ?? "");
$subtitle = trim($_POST["subtitle"] ?? "");
$description = trim($_POST["description"] ?? "");
$buttonText = trim($_POST["button_text"] ?? "");
$buttonLink = trim($_POST["button_link"] ?? "");

$address = trim($_POST["address"] ?? "");
$openingHours = trim($_POST["opening_hours"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$whatsappLink = trim($_POST["whatsapp_link"] ?? "");
$mapLink = trim($_POST["map_link"] ?? "");
$locationName = trim($_POST["location_name"] ?? "");

if (!is_numeric($id)) {
    die("Invalid content ID.");
}

if ($title === "") {
    die("Title is required.");
}


/*
|--------------------------------------------------------------------------
| GET CURRENT CONTENT
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        section,
        image,
        image_2,
        image_3
    FROM website_content
    WHERE id = :id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":id" => $id
]);

$content = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$content) {
    die("Website content not found.");
}

$oldImage = $content["image"];
$oldImage2 = $content["image_2"];
$oldImage3 = $content["image_3"];

$imagePath = $oldImage;
$imagePath2 = $oldImage2;
$imagePath3 = $oldImage3;


/*
|--------------------------------------------------------------------------
| UPLOAD DIRECTORY
|--------------------------------------------------------------------------
*/

$uploadDirectory = "../frontend/images/";

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}


/*
|--------------------------------------------------------------------------
| IMAGE UPLOAD FUNCTION
|--------------------------------------------------------------------------
*/

function uploadImage($file, $uploadDirectory)
{
    if ($file["error"] !== UPLOAD_ERR_OK) {
        die("Image upload failed.");
    }

    if ($file["size"] > 2 * 1024 * 1024) {
        die("Image must be 2MB or smaller.");
    }

    $allowedTypes = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    $fileType = mime_content_type($file["tmp_name"]);

    if (!isset($allowedTypes[$fileType])) {
        die("Only JPG, PNG or WebP images are allowed.");
    }

    $extension = $allowedTypes[$fileType];

    $filename = uniqid("website_", true) . "." . $extension;

    $destination = $uploadDirectory . $filename;

    if (!move_uploaded_file(
        $file["tmp_name"],
        $destination
    )) {
        die("Failed to save image.");
    }

    return "images/" . $filename;
}


/*
|--------------------------------------------------------------------------
| HERO IMAGE
|--------------------------------------------------------------------------
*/

if (
    $content["section"] === "hero" &&
    isset($_FILES["image"]) &&
    $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
) {

    $imagePath = uploadImage(
        $_FILES["image"],
        $uploadDirectory
    );
}


/*
|--------------------------------------------------------------------------
| SPACE IMAGES
|--------------------------------------------------------------------------
*/

if ($content["section"] === "space") {

    // Interior

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        $imagePath = uploadImage(
            $_FILES["image"],
            $uploadDirectory
        );
    }


    // Outdoor

    if (
        isset($_FILES["image_2"]) &&
        $_FILES["image_2"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        $imagePath2 = uploadImage(
            $_FILES["image_2"],
            $uploadDirectory
        );
    }


    // Night

    if (
        isset($_FILES["image_3"]) &&
        $_FILES["image_3"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        $imagePath3 = uploadImage(
            $_FILES["image_3"],
            $uploadDirectory
        );
    }
}


/*
|--------------------------------------------------------------------------
| UPDATE DATABASE
|--------------------------------------------------------------------------
*/

$sql = "
    UPDATE website_content
    SET
        title = :title,
        subtitle = :subtitle,
        description = :description,
        button_text = :button_text,
        button_link = :button_link,
        image = :image,
        image_2 = :image_2,
        image_3 = :image_3,
        address = :address,
        opening_hours = :opening_hours,
        phone = :phone,
        whatsapp_link = :whatsapp_link,
        map_link = :map_link,
        location_name = :location_name
    WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":title" => $title,
    ":subtitle" => $subtitle,
    ":description" => $description,
    ":button_text" => $buttonText,
    ":button_link" => $buttonLink,
    ":image" => $imagePath,
    ":image_2" => $imagePath2,
    ":image_3" => $imagePath3,

    ":address" => $address,
    ":opening_hours" => $openingHours,
    ":phone" => $phone,
    ":whatsapp_link" => $whatsappLink,
    ":map_link" => $mapLink,
    ":location_name" => $locationName,

    ":id" => $id
]);


/*
|--------------------------------------------------------------------------
| DELETE OLD IMAGES
|--------------------------------------------------------------------------
*/

if (
    $imagePath !== $oldImage &&
    !empty($oldImage)
) {

    $oldImageFile = "../frontend/" . $oldImage;

    if (file_exists($oldImageFile)) {
        unlink($oldImageFile);
    }
}


if (
    $imagePath2 !== $oldImage2 &&
    !empty($oldImage2)
) {

    $oldImageFile2 = "../frontend/" . $oldImage2;

    if (file_exists($oldImageFile2)) {
        unlink($oldImageFile2);
    }
}


if (
    $imagePath3 !== $oldImage3 &&
    !empty($oldImage3)
) {

    $oldImageFile3 = "../frontend/" . $oldImage3;

    if (file_exists($oldImageFile3)) {
        unlink($oldImageFile3);
    }
}


/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

header("Location: content.php?success=updated");
exit;

?>