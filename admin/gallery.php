<?php

require_once '../backend/config/database.php';

$stmt = $pdo->query("
    SELECT *
    FROM gallery
    ORDER BY sort_order ASC, id ASC
");

$gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gallery Management</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f1ea;
        }

        h1 {
            margin-bottom: 30px;
        }

        .gallery-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
         }

         .gallery-item {
            overflow: hidden;
            aspect-ratio: 4 / 3;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .gallery-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
        }

        .gallery-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            margin-bottom: 15px;
        }

        .gallery-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .gallery-path {
            font-size: 13px;
            color: #777;
        }

        .gallery-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .gallery-header h1 {
            margin: 0;
        }

        .add-gallery-btn {
            display: inline-block;
            padding: 12px 20px;
            background: #1d1b19;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .add-gallery-btn:hover {
            background: #3a3632;
        }

        .gallery-actions {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .gallery-actions a {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
        }

        .btn-edit {
            background: #1d1b19;
            color: white;
        }

        .btn-toggle {
            background: #e2d8c8;
            color: #1d1b19;
        }

        .btn-delete {
            background: #f1d5d5;
            color: #8b2e2e;
        }

        .gallery-actions a:hover {
            opacity: 0.8;
        }

        .gallery-heading .section-label {
            font-family: inherit;
            letter-spacing: 4px;
        }

        .gallery-title {
            font-family: Georgia, serif;
            font-weight: 400;
        }


        @media (max-width: 1200px) {

                .gallery-grid {
                    grid-template-columns: repeat(4, 1fr);
                }

            }

            @media (max-width: 768px) {

                .gallery-grid {
                    grid-template-columns: repeat(2, 1fr);
                }

            }

            @media (max-width: 480px) {

                .gallery-grid {
                    grid-template-columns: 1fr;
                }

            }

            .gallery-header-actions {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .back-btn {
                display: inline-block;
                padding: 12px 20px;
                background: #e2d8c8;
                color: #1d1b19;
                text-decoration: none;
                border-radius: 4px;
            }

            .back-btn:hover {
                background: #d5c9b8;
            }

    </style>

</head>

<body>

    <div class="gallery-header">

    <h1>Gallery Management</h1>

    <div class="gallery-header-actions">

        <a href="dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

        <a href="gallery-create.php" class="add-gallery-btn">
            + Add New Image
        </a>

    </div>

</div>

    <div class="gallery-grid">

        <?php foreach ($gallery as $item): ?>

            <div class="gallery-card">

                <img
                    src="../frontend/<?php echo htmlspecialchars($item['image']); ?>"
                    alt="<?php echo htmlspecialchars($item['title'] ?? 'Gallery'); ?>"
                >

                <div class="gallery-title">
                    <?php echo htmlspecialchars($item['title'] ?? 'Untitled'); ?>
                </div>

                <div class="gallery-path">
                    <?php echo htmlspecialchars($item['image']); ?>
                </div>
                <div class="gallery-actions">

                    <a
                        href="gallery-edit.php?id=<?php echo $item['id']; ?>"
                        class="btn-edit"
                    >
                        Change Image
                    </a>

                    <a
                        href="gallery-toggle.php?id=<?php echo $item['id']; ?>"
                        class="btn-toggle"
                    >
                        <?php echo $item['status'] == 1 ? 'Hide' : 'Show'; ?>
                    </a>

                    <a
                        href="gallery-delete.php?id=<?php echo $item['id']; ?>"
                        class="btn-delete"
                        onclick="return confirm('Are you sure you want to delete this image?');"
                    >
                        Delete
                    </a>

                </div>


            </div>

        <?php endforeach; ?>

    </div>

</body>
</html>