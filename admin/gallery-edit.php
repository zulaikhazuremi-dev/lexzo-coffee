<?php

require_once '../backend/config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: gallery.php');
    exit;
}

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Change Gallery Image</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f1ea;
        }

        .form-container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            margin-top: 0;
            margin-bottom: 30px;
        }

        .current-image {
            margin-bottom: 25px;
        }

        .current-image img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            border: none;
            background: #1d1b19;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn:hover {
            background: #3a3632;
        }

        .cancel {
            margin-left: 10px;
            color: #333;
            text-decoration: none;
        }

    </style>

</head>

<body>

    <div class="form-container">

        <h1>Change Gallery Image</h1>


        <div class="current-image">

            <p>
                <strong>Current Image</strong>
            </p>

            <img
                src="../frontend/<?php echo htmlspecialchars($gallery['image']); ?>"
                alt="<?php echo htmlspecialchars($gallery['title'] ?? 'Gallery'); ?>"
            >

        </div>


        <form
            action="gallery-update.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <input
                type="hidden"
                name="id"
                value="<?php echo $gallery['id']; ?>"
            >


            <div class="form-group">

                <label for="title">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo htmlspecialchars($gallery['title'] ?? ''); ?>"
                >

            </div>


            <div class="form-group">

                <label for="image">
                    New Image
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/jpeg,image/png,image/webp"
                    required
                >

            </div>


            <div class="form-group">

                <label for="sort_order">
                    Sort Order
                </label>

                <input
                    type="number"
                    id="sort_order"
                    name="sort_order"
                    value="<?php echo $gallery['sort_order']; ?>"
                    min="0"
                >

            </div>


            <button type="submit" class="btn">
                Update Image
            </button>

            <a href="gallery.php" class="cancel">
                Cancel
            </a>

        </form>

    </div>

</body>

</html>