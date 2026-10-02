<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";

$sql = "
    SELECT
        id,
        section,
        title,
        subtitle,
        description,
        button_text,
        button_link,
        image,
        updated_at
    FROM website_content
    ORDER BY id
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$contents = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Website Content | Lexzo Coffee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container py-5">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="mb-1">
                Website Content
            </h1>

            <p class="text-muted mb-0">
                Manage your website content.
            </p>

        </div>

        <a
            href="dashboard.php"
            class="btn btn-outline-dark">

            Back to Dashboard

        </a>

    </div>


    <!-- CONTENT TABLE -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Section
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Image
                            </th>

                            <th>
                                Updated
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php if (empty($contents)): ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4">

                                No website content found.

                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($contents as $content): ?>

                            <tr>

                                <td class="px-3">
                                    <?= $content["id"] ?>
                                </td>


                                <td>

                                    <strong>
                                        <?= htmlspecialchars(
                                            ucfirst($content["section"])
                                        ) ?>
                                    </strong>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $content["title"] ?? ""
                                    ) ?>

                                </td>


                                <td>

                                    <?php if (!empty($content["image"])): ?>

                                        <img
                                            src="../frontend/<?= htmlspecialchars($content["image"]) ?>"
                                            alt="<?= htmlspecialchars($content["section"]) ?>"
                                            style="
                                                width: 60px;
                                                height: 60px;
                                                object-fit: cover;
                                                border-radius: 8px;
                                            ">

                                    <?php else: ?>

                                        <span class="text-muted small">
                                            No image
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <small class="text-muted">

                                        <?= htmlspecialchars(
                                            $content["updated_at"]
                                        ) ?>

                                    </small>

                                </td>


                                <td>

                                    <a
                                        href="content-edit.php?id=<?= $content["id"] ?>"
                                        class="btn btn-sm btn-outline-dark">

                                        Edit

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>

</html>