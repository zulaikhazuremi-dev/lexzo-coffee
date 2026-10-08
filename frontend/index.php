

<?php

require_once "../backend/config/database.php";


// Get Active Categories
$categorySql = "
    SELECT id, name
    FROM categories
    WHERE status = 1
    ORDER BY sort_order ASC
";

$categoryStmt = $pdo->prepare($categorySql);
$categoryStmt->execute();

$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);


// Get 3 Featured Items For Each Category

$menuItems = [];

foreach ($categories as $category) {

    $menuSql = "
        SELECT
            id,
            category_id,
            name,
            description,
            price,
            price_type,
            image
        FROM menu_items
        WHERE category_id = :category_id
        AND status = 1
        ORDER BY sort_order ASC
        LIMIT 3
    ";

    $menuStmt = $pdo->prepare($menuSql);

    $menuStmt->execute([
        ":category_id" => $category["id"]
    ]);

    $menuItems[$category["id"]] =
        $menuStmt->fetchAll(PDO::FETCH_ASSOC);
}

?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lexzo Coffee</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>



<body>

    <nav class="navbar navbar-expand-lg lexzo-navbar">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand lexzo-logo" href="#">
            <img src="images/lexzo-logo.png" alt="Lexzo Coffee">
        </a>

        <!-- Mobile Toggle -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarContent">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="#menu">
                        Menu
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#space">
                        Space
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#visit">
                        Visit
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="nav-order" href="#order">
                        Order
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>


<!-- =========================
     HERO SECTION
========================= -->

<section class="hero-section">

    <div class="container-fluid px-lg-5">

        <div class="row align-items-center hero-row">

            <!-- Hero Content -->
            <div class="col-lg-6 hero-content">

                <p class="hero-label">
                    LEXZO COFFEE · KUALA KANGSAR
                </p>

              

                <h1 class="hero-title" id="hero-title"></h1>

                <p class="hero-description" id="hero-description"></p>

                <div class="hero-buttons">

                    <a href="#menu" class="btn-primary-lexzo">
                        Explore Menu
                    </a>

                    <a href="#visit" class="btn-secondary-lexzo">
                        Visit Us
                    </a>

                </div>

            </div>

            <div class="col-lg-6 hero-image-wrapper">

            <div class="hero-cup">



                <!-- <div class="steam">
                    <span></span>
                    <span></span>
                    <span></span>
                </div> -->

                <!-- <img
                    src="images/lexzo-coffee-cup.png"
                    alt="Lexzo Coffee"
                    class="hero-cup-image"> -->

                    <!-- <img
                    src="images/Minimalist-Coffee.png"
                    alt="Lexzo Coffee"
                    class="hero-cup-image">

            </div>

        </div> -->

        </div>

    </div>

</section>


<!-- =========================
     MENU SECTION
========================= -->

<section class="menu-section" id="menu">

    <div class="container">

        <!-- Menu Heading -->
        <div class="menu-heading text-center">

            <p class="menu-label">
                OUR MENU
            </p>

            <h2 class="menu-title">
                Something good<br>
                for every mood.
            </h2>

            <p class="menu-description">
                From comforting meals to carefully crafted coffee,
                there's always something worth staying for.
            </p>

        </div>


      

        <div class="menu-categories">

            <?php foreach ($categories as $index => $category): ?>

                <a href="#category-<?php echo $category['id']; ?>"
                class="menu-category <?php echo $index === 0 ? 'active' : ''; ?>">

                    <?php echo htmlspecialchars($category['name']); ?>

                </a>

            <?php endforeach; ?>

        </div>


        <!-- Featured Menu -->

<?php foreach ($categories as $category): ?>

    <div
        class="menu-category-content <?php echo $category['id'] == $categories[0]['id'] ? 'active' : ''; ?>"
        id="category-<?php echo $category['id']; ?>">

        <div class="row g-4 menu-grid">

            <?php foreach ($menuItems[$category['id']] as $item): ?>

                <div class="col-md-4">

                    <div class="menu-card">

                        <div class="menu-card-image">

                            <?php if (!empty($item["image"])): ?>

                                <img
                                    src="<?php echo htmlspecialchars($item["image"]); ?>"
                                    alt="<?php echo htmlspecialchars($item["name"]); ?>">

                            <?php endif; ?>

                        </div>


                        <div class="menu-card-content">

                            <p class="menu-card-category">
                                <?php echo htmlspecialchars($category["name"]); ?>
                            </p>


                            <h3>
                                <?php echo htmlspecialchars($item["name"]); ?>
                            </h3>


                            <p>
                                <?php echo htmlspecialchars($item["description"]); ?>
                            </p>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php endforeach; ?>


        <!-- View Full Menu -->
        <div class="menu-view-all">

            <a href="menu.html" class="menu-link">
                View Full Menu →
            </a>

        </div>

    </div>

</section>

<!-- =========================
     ABOUT SECTION
========================= -->

<!-- <section class="about-section" id="about">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <p class="section-label">
                    OUR STORY
                </p>

                <h2 class="about-title" id="story-title">
                    More than coffee,<br>
                    it's a place to unwind.
                </h2>

            </div>

            <div class="col-lg-6">

                <p class="about-text" id="story-description">
                    Lexzo Coffee was created as a space where people can
                    slow down, enjoy quality coffee and spend meaningful
                    moments with friends and family.
                </p>

                <p class="about-text">
                    Whether you're here for your morning coffee, a casual
                    meeting, or simply a quiet break, Lexzo offers a warm
                    and comfortable atmosphere designed for everyone.
                </p>

            </div>

        </div>

    </div>

</section> -->

<section class="about-section" id="about">

    <!-- Background Image -->
    <div class="about-image-wrapper">
        <div class="about-image">
            <img
                src="images/story-coffee.png"
                alt="Coffee at Lexzo Coffee"
            >
        </div>
    </div>


    <!-- Content -->
    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6 about-content">

                <p class="section-label">
                    OUR STORY
                </p>

                <h2 class="about-title" id="story-title">
                    More than coffee,
                    it's a place to unwind.
                </h2>

                <div class="about-line"></div>

                <p class="about-text" id="story-description">
                    Lexzo Coffee was created as a space where people can
                    slow down, enjoy quality coffee and spend meaningful
                    moments with friends and family.
                </p>

                <p class="about-text">
                    Whether you're here for your morning coffee, a casual
                    meeting, or simply a quiet break, Lexzo offers a warm
                    and comfortable atmosphere designed for everyone.
                </p>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     SPACE SECTION
========================= -->

<section class="space-section" id="space">

    <div class="container">

        <!-- Section Heading -->
        <div class="space-heading">

            <p class="section-label">
                THE SPACE
            </p>

            <h2 class="space-title" id="space-title">
                A place to stay<br>
                a little longer.
            </h2>

        </div>


        <!-- Image Gallery -->
        <div class="space-gallery">

            <!-- Large Interior Image -->
            <div class="space-image space-image-large">

                <img
                    id="space-image"
                    src="images/space-interior.jpg"
                    alt="Lexzo Coffee interior">

            </div>


            <!-- Right Images -->
            <div class="space-side">

                <div class="space-image">

                    <img
                        id="space-image-2"
                        src="images/space-outdoor.jpg"
                        alt="Lexzo Coffee outdoor seating">

                </div>


                <div class="space-image">

                    <img
                        id="space-image-3"
                        src="images/space-night.jpg"
                        alt="Lexzo Coffee at night">

                </div>

            </div>

        </div>


        <!-- Description -->
        <div class="space-description">

            <p id="space-description">
                From a quiet coffee break to conversations with friends
                and family, Lexzo Coffee offers a comfortable space to
                slow down and enjoy the moment.
            </p>

        </div>

    </div>

</section>


<!-- =========================
     GALLERY SECTION
========================= -->

<section class="gallery-section" id="gallery">

    <div class="container">

        <div class="gallery-heading">

            <p class="section-label">
                GALLERY
            </p>

            <h2 class="gallery-title">
                Gallery of Happiness
            </h2>

        </div>


       <div class="gallery-grid" id="gallery-grid"></div>

    </div>

</section>

<!-- =========================
     VISIT US SECTION
========================= -->

<section class="visit-section" id="visit">

    <div class="container">

        <div class="row align-items-start">

            <!-- Visit Information -->
            <div class="col-lg-5 visit-content">

                <p class="section-label">
                    VISIT US
                </p>

                <h2 class="visit-title">
                    Come by,<br>
                    stay a while.
                </h2>

                <p class="visit-description">
                    Whether you're meeting friends, enjoying a quiet
                    coffee, or simply taking a break, we'd love to have
                    you here.
                </p>


                <!-- Address -->
                <div class="visit-item">

                    <span class="visit-item-label">
                        ADDRESS
                    </span>

                    <p id="visit-address">
                        7A, Jalan Tun Razak,<br>
                        Taman Mawar,<br>
                        33000 Kuala Kangsar,<br>
                        Perak, Malaysia
                    </p>

                </div>


                <!-- Opening Hours -->
                <div class="visit-item">

                    <span class="visit-item-label">
                        OPENING HOURS
                    </span>

                  

                    <p id="visit-opening-hours">
                        Monday – Saturday
                        10:00 AM – 11:30 PM

                        Sunday
                        4:00 PM – 11:30 PM
                    </p>

                </div>


                <!-- Phone -->
                <div class="visit-item">

                    <span class="visit-item-label">
                        PHONE
                    </span>

                    <p>
                        <a id="visit-phone" href="tel:+60195670054">
                            019-567 0054
                        </a>
                    </p>

                    <a id="visit-whatsapp" href="https://wa.me/60195670054"
                    class="whatsapp-link"
                    target="_blank">
                        WhatsApp us →
                    </a>

                </div>


                

            </div>


            <!-- Visit Image -->
            <div class="col-lg-7 visit-image-wrapper">

                <div class="visit-image">

                    <img
                        src="images/space-outdoor.jpg"
                        alt="Lexzo Coffee outdoor space">

                    <div class="map-overlay">

                        <span>
                            LEXZO COFFEE
                        </span>

                        <strong id="visit-location-name">
                            Kuala Kangsar
                        </strong>

                        <a
                            id="visit-map-link"    
                            href="https://www.google.com/maps/search/?api=1&query=LEXZO+Coffee+Kuala+Kangsar"
                            target="_blank">

                            Open in Google Maps →

                        </a>

                    

                    </div>

                    

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================
     ORDER CTA SECTION
========================= -->

<section class="order-section" id="order">

    <div class="container">

        <div class="order-content">

            <p class="section-label">
                ORDER NOW
            </p>

            <h2 class="order-title">
                Good coffee.<br>
                Good food.<br>
                <span>Good moments.</span>
            </h2>

            <p class="order-description">
                Craving something from Lexzo?
                Order your favourites and enjoy them
                wherever you are.
            </p>

            <a
                href="https://www.foodpanda.my/restaurant/kbf7/lexzo-coffee"
                target="_blank"
                class="order-button">

                ORDER ON FOODPANDA →

            </a>

        </div>

    </div>

</section>

<!-- =========================
     FOOTER
========================= -->

<footer class="lexzo-footer">

    <div class="container">

        <div class="row">

            <!-- Brand -->
            <div class="col-lg-4 footer-brand">

                <a href="#" class="footer-logo">
                    LEXZO
                </a>

                <p>
                    Relax means coffee.
                </p>

            </div>


            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 footer-links">

                <span class="footer-label">
                    EXPLORE
                </span>

                <a href="#menu">Menu</a>
                <a href="#space">Space</a>
                <a href="#visit">Visit Us</a>
                <a href="#order">Order</a>

            </div>


            <!-- Contact -->
            <div class="col-lg-3 col-md-6 footer-contact">

                <span class="footer-label">
                    FIND US
                </span>

                <p>
                    7A, Jalan Tun Razak,<br>
                    Taman Mawar,<br>
                    33000 Kuala Kangsar,<br>
                    Perak, Malaysia
                </p>

                <a href="tel:+60195670054">
                    019-567 0054
                </a>

            </div>

            <!-- Social Media -->
                <div class="col-lg-3 col-md-6 footer-links">

                    <span class="footer-label">
                        FOLLOW
                    </span>

                    <a href="LINK_INSTAGRAM" target="_blank">Instagram</a>
                    <a href="LINK_TIKTOK" target="_blank">TikTok</a>
                    <a href="LINK_FACEBOOK" target="_blank">Facebook</a>
                    <a href="LINK_THREADS" target="_blank">Threads</a>

                </div>

        </div>


        <!-- Bottom -->
        <div class="footer-bottom">

            <p>
                © 2026 Lexzo Coffee. All rights reserved.
            </p>

            <p>
                Kuala Kangsar, Perak
            </p>

        </div>

    </div>

</footer>


    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- Custom JS -->
    <script src="js/script.js"></script>

</body>
</html>