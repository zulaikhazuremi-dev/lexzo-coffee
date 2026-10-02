<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";


/*
|--------------------------------------------------------------------------
| Get Menu ID
|--------------------------------------------------------------------------
*/

$id = $_GET["id"] ?? "";

if (!is_numeric($id)) {
    die("Invalid menu ID.");
}


/*
|--------------------------------------------------------------------------
| Get Menu Item
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        category_id,
        name,
        description,
        price,
        price_type,
        image,
        sort_order,
        status
    FROM menu_items
    WHERE id = :id
    LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":id" => $id
]);

$menu = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$menu) {
    die("Menu item not found.");
}


/*
|--------------------------------------------------------------------------
| Get Categories
|--------------------------------------------------------------------------
*/

$categorySql = "
    SELECT id, name
    FROM categories
    WHERE status = 1
    ORDER BY sort_order
";

$categoryStmt = $pdo->prepare($categorySql);
$categoryStmt->execute();

$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Edit Menu | Lexzo Coffee</title>

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

        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="mb-4">

                    <a
                        href="menu.php"
                        class="text-decoration-none">

                        ← Back to Menu

                    </a>

                </div>


                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4 p-md-5">

                        <h1 class="mb-2">
                            Edit Menu
                        </h1>

                        <p class="text-muted mb-4">
                            Update this menu item.
                        </p>


                        <form
                            action="menu-update.php"
                            method="POST"
                            enctype="multipart/form-data">


                            <!-- Hidden ID -->

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $menu["id"] ?>">


                            <!-- Category -->

                            <div class="mb-3">

                                <label class="form-label">
                                    Category
                                </label>

                                <select
                                    name="category_id"
                                    class="form-select"
                                    required>

                                    <?php foreach ($categories as $category): ?>

                                        <option
                                            value="<?= $category["id"] ?>"
                                            <?= $menu["category_id"] == $category["id"] ? "selected" : "" ?>>

                                            <?= htmlspecialchars($category["name"]) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- Name -->

                            <div class="mb-3">

                                <label class="form-label">
                                    Menu Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="<?= htmlspecialchars($menu["name"]) ?>"
                                    required>

                            </div>


                            <!-- Description -->

                            <div class="mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="3"><?= htmlspecialchars($menu["description"] ?? "") ?></textarea>

                                    <!-- Image -->

                                <!-- Image -->
                            <div class="mb-4">

                                <label class="form-label">
                                    Menu Image
                                </label>

                                <?php if (!empty($menu["image"])): ?>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Current Image
                                        </label>

                                        <div>
                                            <img
                                                src="../frontend/<?= htmlspecialchars($menu["image"]) ?>"
                                                alt="<?= htmlspecialchars($menu["name"]) ?>"
                                                style="
                                                    width: 180px;
                                                    height: 180px;
                                                    object-fit: cover;
                                                    border-radius: 10px;
                                                ">
                                        </div>
                                    </div>

                                <?php endif; ?>

                                <input
                                    type="file"
                                    name="image"
                                    class="form-control"
                                    accept="image/jpeg,image/png,image/webp">

                                <div class="form-text">
                                    Optional. JPG, PNG or WebP. Maximum 2MB.
                                    Leave empty to keep the current image.
                                </div>

                            </div>

                            </div>


                            <!-- Price -->

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Price
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            RM
                                        </span>

                                        <input
                                            type="number"
                                            name="price"
                                            class="form-control"
                                            step="0.01"
                                            min="0"
                                            value="<?= htmlspecialchars($menu["price"]) ?>"
                                            required>

                                    </div>

                                </div>


                                <!-- Price Type -->

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Price Type
                                    </label>

                                    <select
                                        name="price_type"
                                        class="form-select"
                                        required>

                                        <option
                                            value="fixed"
                                            <?= $menu["price_type"] === "fixed" ? "selected" : "" ?>>

                                            Fixed

                                        </option>

                                        <option
                                            value="from"
                                            <?= $menu["price_type"] === "from" ? "selected" : "" ?>>

                                            From

                                        </option>

                                    </select>

                                </div>

                            </div>


                            <!-- Sort Order -->

                            <div class="mb-3">

                                <label class="form-label">
                                    Sort Order
                                </label>

                                <input
                                    type="number"
                                    name="sort_order"
                                    class="form-control"
                                    min="0"
                                    value="<?= htmlspecialchars($menu["sort_order"]) ?>">

                            </div>


                            <!-- Status -->

                            <div class="mb-4">

                                <label class="form-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="form-select"
                                    required>

                                    <option
                                        value="1"
                                        <?= $menu["status"] == 1 ? "selected" : "" ?>>

                                        Active

                                    </option>

                                    <option
                                        value="0"
                                        <?= $menu["status"] == 0 ? "selected" : "" ?>>

                                        Hidden

                                    </option>

                                </select>

                            </div>


                            <!-- Buttons -->

                            <div class="d-flex gap-2">

                                <a
                                    href="menu.php"
                                    class="btn btn-outline-secondary">

                                    Cancel

                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-dark">

                                    Save Changes

                                </button>

                            </div>


                        </form>

                    </div>

                </div>

            </div>

        </div>

    </main>

</body>

</html>