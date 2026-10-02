<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../backend/config/database.php";

$id = $_GET["id"] ?? "";

if (!is_numeric($id)) {
    die("Invalid content ID.");
}

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
        image_2,
        image_3,
        address,
        opening_hours,
        phone,
        whatsapp_link,
        map_link,
        location_name
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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Edit <?= htmlspecialchars(ucfirst($content["section"])) ?>
        | Lexzo Coffee
    </title>

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
                Edit Website Content
            </h1>

            <p class="text-muted mb-0">
                <?= htmlspecialchars(ucfirst($content["section"])) ?> section
            </p>

        </div>

        <a
            href="content.php"
            class="btn btn-outline-dark">

            Back

        </a>

    </div>


    <!-- FORM -->

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form
                action="content-update.php"
                method="POST"
                enctype="multipart/form-data">

                <input
                    type="hidden"
                    name="id"
                    value="<?= $content["id"] ?>">


                <!-- SECTION -->

                <div class="mb-4">

                    <label class="form-label">
                        Section
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            ucfirst($content["section"])
                        ) ?>"
                        disabled>

                </div>


                <!-- TITLE -->

                <div class="mb-4">

                    <label class="form-label">
                        Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $content["title"] ?? ""
                        ) ?>"
                        required>

                </div>


                <!-- SUBTITLE -->

                <div class="mb-4">

                    <label class="form-label">
                        Subtitle
                    </label>

                    <input
                        type="text"
                        name="subtitle"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $content["subtitle"] ?? ""
                        ) ?>">

                </div>


                <!-- DESCRIPTION -->

                <div class="mb-4">

                    <label class="form-label">
                        Description
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"><?= htmlspecialchars(
                            $content["description"] ?? ""
                        ) ?></textarea>

                </div>


                <!-- BUTTON TEXT -->

                <!-- <div class="mb-4">

                    <label class="form-label">
                        Button Text
                    </label>

                    <input
                        type="text"
                        name="button_text"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $content["button_text"] ?? ""
                        ) ?>">

                </div> -->


                <!-- BUTTON LINK -->

                <!-- <div class="mb-4">

                    <label class="form-label">
                        Button Link
                    </label>

                    <input
                        type="text"
                        name="button_link"
                        class="form-control"
                        value="<?= htmlspecialchars(
                            $content["button_link"] ?? ""
                        ) ?>">

                </div> -->

                <?php if (($content['section'] ?? '') === 'hero'): ?>

                    <div class="mb-3">
                        <label>Button Text</label>
                        <input
                            type="text"
                            name="button_text"
                            class="form-control"
                            value="<?= htmlspecialchars($content['button_text'] ?? '') ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label>Button Link</label>
                        <input
                            type="text"
                            name="button_link"
                            class="form-control"
                            value="<?= htmlspecialchars($content['button_link'] ?? '') ?>"
                        >
                    </div>

                <?php endif; ?>

                <?php if (($content['section'] ?? '') === 'visit'): ?>

                <hr class="my-4">

                <h4 class="mb-3">Visit Us Information</h4>

                <!-- Address -->
                <div class="mb-3">
                    <label class="form-label">Address</label>

                    <textarea
                        name="address"
                        class="form-control"
                        rows="4"
                    ><?= htmlspecialchars($content['address'] ?? '') ?></textarea>
                </div>

                <!-- Opening Hours -->
                <div class="mb-3">
                    <label class="form-label">Opening Hours</label>

                    <textarea
                        name="opening_hours"
                        class="form-control"
                        rows="5"
                    ><?= htmlspecialchars($content['opening_hours'] ?? '') ?></textarea>
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label class="form-label">Phone</label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($content['phone'] ?? '') ?>"
                    >
                </div>

                <!-- WhatsApp Link -->
                <div class="mb-3">
                    <label class="form-label">WhatsApp Link</label>

                    <input
                        type="text"
                        name="whatsapp_link"
                        class="form-control"
                        value="<?= htmlspecialchars($content['whatsapp_link'] ?? '') ?>"
                        placeholder="https://wa.me/60123456789"
                    >
                </div>

                <!-- Google Maps Link -->
                <div class="mb-3">
                    <label class="form-label">Google Maps Link</label>

                    <input
                        type="text"
                        name="map_link"
                        class="form-control"
                        value="<?= htmlspecialchars($content['map_link'] ?? '') ?>"
                        placeholder="https://www.google.com/maps/..."
                    >
                </div>

                <!-- Location Name -->
                <div class="mb-3">
                    <label class="form-label">Location Name</label>

                    <input
                        type="text"
                        name="location_name"
                        class="form-control"
                        value="<?= htmlspecialchars($content['location_name'] ?? '') ?>"
                        placeholder="Kuala Kangsar"
                    >
                </div>

            <?php endif; ?>


    <?php if ($content["section"] === "hero"): ?>

    <!-- HERO IMAGE -->

    <div class="mb-4">

        <label class="form-label">
            Hero Image
        </label>

        <?php if (!empty($content["image"])): ?>

            <div class="mb-3">

                <label class="form-label">
                    Current Image
                </label>

                <div>

                    <img
                        src="../frontend/<?= htmlspecialchars(
                            $content["image"]
                        ) ?>"
                        alt="Hero Image"
                        style="
                            width: 300px;
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
            Optional. JPG, PNG or WebP.
            Maximum 2MB.
            Leave empty to keep the current image.
        </div>

    </div>


<?php elseif ($content["section"] === "space"): ?>

    <!-- SPACE IMAGES -->

    <div class="mb-4">

        <label class="form-label">
            Space Images
        </label>

        <!-- IMAGE 1 -->

        <?php if (!empty($content["image"])): ?>

            <div class="mb-3">

                <label class="form-label">
                    Current Interior Image
                </label>

                <div>

                    <img
                        src="../frontend/<?= htmlspecialchars(
                            $content["image"]
                        ) ?>"
                        alt="Space Interior"
                        style="
                            width: 220px;
                            height: 150px;
                            object-fit: cover;
                            border-radius: 10px;
                        ">

                </div>

            </div>

        <?php endif; ?>

        <label class="form-label">
            Replace Interior Image
        </label>

        <input
            type="file"
            name="image"
            class="form-control mb-4"
            accept="image/jpeg,image/png,image/webp">


        <!-- IMAGE 2 -->

        <?php if (!empty($content["image_2"])): ?>

            <div class="mb-3">

                <label class="form-label">
                    Current Outdoor Image
                </label>

                <div>

                    <img
                        src="../frontend/<?= htmlspecialchars(
                            $content["image_2"]
                        ) ?>"
                        alt="Space Outdoor"
                        style="
                            width: 220px;
                            height: 150px;
                            object-fit: cover;
                            border-radius: 10px;
                        ">

                </div>

            </div>

        <?php endif; ?>

        <label class="form-label">
            Replace Outdoor Image
        </label>

        <input
            type="file"
            name="image_2"
            class="form-control mb-4"
            accept="image/jpeg,image/png,image/webp">


        <!-- IMAGE 3 -->

        <?php if (!empty($content["image_3"])): ?>

            <div class="mb-3">

                <label class="form-label">
                    Current Night Image
                </label>

                <div>

                    <img
                        src="../frontend/<?= htmlspecialchars(
                            $content["image_3"]
                        ) ?>"
                        alt="Space Night"
                        style="
                            width: 220px;
                            height: 150px;
                            object-fit: cover;
                            border-radius: 10px;
                        ">

                </div>

            </div>

        <?php endif; ?>

        <label class="form-label">
            Replace Night Image
        </label>

        <input
            type="file"
            name="image_3"
            class="form-control"
            accept="image/jpeg,image/png,image/webp">

        <div class="form-text mt-2">
            Optional. JPG, PNG or WebP.
            Maximum 2MB per image.
            Leave empty to keep the current images.
        </div>

    </div>

<?php endif; ?>


                <!-- BUTTONS -->

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-dark">

                        Save Changes

                    </button>

                    <a
                        href="content.php"
                        class="btn btn-outline-secondary">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

</body>

</html>