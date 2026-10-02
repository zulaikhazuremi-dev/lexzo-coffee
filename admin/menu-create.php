<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";

$sql = "
    SELECT id, name
    FROM categories
    WHERE status = 1
    ORDER BY sort_order
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Add Menu | Lexzo Coffee</title>

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
                            Add New Menu
                        </h1>

                        <p class="text-muted mb-4">
                            Add a new menu item to Lexzo Coffee.
                        </p>


                        <form
                            action="menu-store.php"
                            method="POST"
                            enctype="multipart/form-data">


                            <!-- Category -->

                            <div class="mb-3">

                                <label class="form-label">
                                    Category
                                </label>

                                <select
                                    name="category_id"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select category
                                    </option>

                                    <?php foreach ($categories as $category): ?>

                                        <option
                                            value="<?= $category["id"] ?>">

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
                                    placeholder="e.g. Caramel Latte"
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
                                    rows="3"
                                    placeholder="Short description of the menu item"></textarea>

                            </div>

                            <!-- Image -->

                            <div class="mb-4">

                                <label class="form-label">
                                    Menu Image
                                </label>

                                <input
                                    type="file"
                                    name="image"
                                    class="form-control"
                                    accept="image/jpeg,image/png,image/webp">

                                <div class="form-text">
                                    Optional. JPG, PNG or WebP. Maximum 2MB.
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
                                            placeholder="13.30"
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

                                        <option value="fixed">
                                            Fixed
                                        </option>

                                        <option value="from">
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
                                    value="0"
                                    min="0">

                                <div class="form-text">
                                    Lower numbers appear first.
                                </div>

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

                                    <option value="1">
                                        Active
                                    </option>

                                    <option value="0">
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

                                    Add Menu

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