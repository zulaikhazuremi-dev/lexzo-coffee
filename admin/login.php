<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Lexzo Coffee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

    <div class="container">

        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-md-5 col-lg-4">

                <div class="text-center mb-4">

                    <h1>LEXZO</h1>

                    <p class="text-muted">
                        Coffee Admin Panel
                    </p>

                </div>


                <div class="card shadow-sm border-0">

                    <div class="card-body p-4">

                        <h2 class="h4 mb-4">
                            Admin Login
                        </h2>


                        <form action="login-process.php" method="POST">

                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="admin@example.com"
                                    required>

                            </div>


                            <div class="mb-4">

                                <label class="form-label">
                                    Password
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Enter password"
                                    required>

                            </div>


                            <button
                                type="submit"
                                class="btn btn-dark w-100">

                                Login

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>