<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Lexzo Coffee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>
<style>

    body {
    background-color: #f5f1ea;
    color: #1d1b18;
}

.lexzo-navbar {
    background-color: #1d1b18;
}

.card {
    background-color: #ffffff;
}

.btn-dark {
    background-color: #1d1b18;
    border-color: #1d1b18;
}

.btn-dark:hover {
    background-color: #3a3632;
    border-color: #3a3632;
}

    </style>

<body>

    <!-- <nav class="navbar navbar-dark bg-dark"> -->
        <nav class="navbar navbar-dark lexzo-navbar">

        <div class="container">

            <span class="navbar-brand">
                LEXZO Coffee Admin
            </span>

            <a
                href="logout.php"
                class="btn btn-outline-light btn-sm">
                Logout
            </a>

        </div>

    </nav>


    <main class="container py-5">

        <h1 class="mb-2">
            Dashboard
        </h1>

        <p class="text-muted mb-5">
            Welcome, <?= htmlspecialchars($_SESSION["admin_name"]) ?>.
        </p>


        <div class="row g-4">

            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h2 class="h4">
                            Menu
                        </h2>

                        <p class="text-muted">
                            Manage Lexzo Coffee menu items.
                        </p>

                        <a
                            href="menu.php"
                            class="btn btn-dark">
                            Manage Menu
                        </a>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h2 class="h4">
                            Categories
                        </h2>

                        <p class="text-muted">
                            Manage menu categories.
                        </p>

                        <a
                            href="categories.php"
                            class="btn btn-dark">
                            Manage Categories
                        </a>

                    </div>

                </div>

            </div>


            <!-- <div class="col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body p-4">

                        <h2 class="h4">
                            Admin
                        </h2>

                        <p class="text-muted">
                            Logged in as:
                            <strong>
                                <?= htmlspecialchars($_SESSION["admin_email"]) ?>
                            </strong>
                        </p>

                    </div>

                </div>

            </div> -->

            <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <h2 class="h4">
                        Website Content
                    </h2>

                    <p class="text-muted">
                        Manage homepage content.
                    </p>

                    <a
                        href="content.php"
                        class="btn btn-dark">
                        Manage Content
                    </a>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <h2 class="h4">
                        Gallery
                    </h2>

                    <p class="text-muted">
                        Manage Lexzo Coffee gallery images.
                    </p>

                    <a
                        href="gallery.php"
                        class="btn btn-dark">
                        Manage Gallery
                    </a>

                </div>

            </div>

        </div>


        </div>

    </main>

</body>

</html>