<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";

$sql = "
    SELECT
        menu_items.id,
        menu_items.name,
        menu_items.description,
        menu_items.price,
        menu_items.price_type,
        menu_items.image,
        menu_items.sort_order,
        menu_items.status,
        categories.name AS category_name
    FROM menu_items
    INNER JOIN categories
        ON menu_items.category_id = categories.id
    ORDER BY categories.sort_order, menu_items.sort_order
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Manage Menu | Lexzo Coffee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

    <!-- Navbar -->

    <nav class="navbar navbar-dark bg-dark">

        <div class="container">

            <a
                href="dashboard.php"
                class="navbar-brand">
                LEXZO Coffee Admin
            </a>

            <a
                href="logout.php"
                class="btn btn-outline-light btn-sm">
                Logout
            </a>

        </div>

    </nav>


    <!-- Main Content -->

    <main class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h1 class="mb-1">
                    Manage Menu
                </h1>

                <p class="text-muted mb-0">
                    View and manage Lexzo Coffee menu items.
                </p>

            </div>

            <a
                href="menu-create.php"
                class="btn btn-dark">
                + Add New Menu
            </a>

        </div>


        <!-- Menu Table -->

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
                                    Image
                                </th>

                                <th>
                                    Name
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($menuItems)): ?>

                                <tr>

                                    <td
                                        colspan="6"
                                        class="text-center py-5 text-muted">

                                        No menu items found.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($menuItems as $item): ?>

                                    <tr>

                                        <!-- <td class="px-3">
                                            <?= $item["id"] ?>
                                        </td>


                                        <td>

                                            <strong>
                                                <?= htmlspecialchars($item["name"]) ?>
                                            </strong> -->

                                            <td class="px-3">
                                                <?= $item["id"] ?>
                                            </td>

                                                <td>
                                                    <?php if (!empty($item["image"])): ?>

                                                        <img
                                                            src="../frontend/<?= htmlspecialchars($item["image"]) ?>"
                                                            alt="<?= htmlspecialchars($item["name"]) ?>"
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
                                                    <strong>
                                                        <?= htmlspecialchars($item["name"]) ?>
                                                    </strong>

                                            <?php if (!empty($item["description"])): ?>

                                                <br>

                                                <small class="text-muted">
                                                    <?= htmlspecialchars($item["description"]) ?>
                                                </small>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <span class="badge text-bg-secondary">
                                                <?= htmlspecialchars($item["category_name"]) ?>
                                            </span>

                                        </td>


                                        <td>

                                            <?= $item["price_type"] === "from" ? "From " : "" ?>

                                            RM<?= number_format((float) $item["price"], 2) ?>

                                        </td>


                                        <td>

                                            <?php if ($item["status"] == 1): ?>

                                                <span class="badge text-bg-success">
                                                    Active
                                                </span>

                                            <?php else: ?>

                                                <span class="badge text-bg-secondary">
                                                    Hidden
                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <!-- <a
                                                href="menu-edit.php?id=<?= $item["id"] ?>"
                                                class="btn btn-sm btn-outline-dark">

                                                Edit

                                            </a> -->

                                           <div class="d-flex gap-2 flex-nowrap">

                                                <a
                                                    href="menu-edit.php?id=<?= $item["id"] ?>"
                                                    class="btn btn-sm btn-outline-dark">

                                                    Edit

                                                </a>

                                                <a
                                                    href="menu-toggle.php?id=<?= $item["id"] ?>"
                                                    class="btn btn-sm <?= $item["status"] == 1 ? "btn-outline-danger" : "btn-outline-success" ?>"
                                                    onclick="return confirm('Are you sure you want to <?= $item["status"] == 1 ? "hide" : "show" ?> <?= htmlspecialchars($item["name"], ENT_QUOTES) ?>?');">

                                                    <?= $item["status"] == 1 ? "Hide" : "Show" ?>

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </main>

</body>

</html>