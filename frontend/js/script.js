
document.addEventListener("DOMContentLoaded", function () {

     fetch("../backend/api/menu.php")
    //fetch("backend/api/menu.php")
        .then(response => response.json())
        .then(data => {

            console.log("Menu data:", data);

          

            function renderMenu(categoryId, containerId) {

                const menuContainer = document.getElementById(containerId);

                if (!menuContainer) {
                    return;
                }

                const menuItems = data.filter(
                    item => item.category_id == categoryId
                );

                menuItems.forEach(item => {

                    menuContainer.innerHTML += `
                        <div class="menu-item">

                            <div class="row align-items-start">

                                <div class="col">

                                    <h3 class="menu-item-name">
                                        ${item.name}
                                    </h3>

                                    <p class="menu-item-description">
                                        ${item.description ?? ""}
                                    </p>

                                </div>

                                <div class="col-auto">

                                    <span class="menu-item-price">
                                        ${item.price_type === "from" ? "From " : ""}
                                        RM${item.price}
                                    </span>

                                </div>

                            </div>

                        </div>
                    `;

                });
            }


            // =========================
            // MENU CATEGORIES
            // =========================

            renderMenu(1, "coffee-menu");
            renderMenu(2, "food-menu");
            renderMenu(3, "light-bites-menu");
            renderMenu(4, "non-coffee-menu");
            renderMenu(5, "dessert-menu");
            renderMenu(6, "specials-menu");


        })
        .catch(error => {
            console.error("Error fetching menu:", error);
        });

});

// =========================
// WEBSITE CONTENT
// =========================

 fetch("../backend/api/content.php")
//fetch("backend/api/content.php")
    .then(response => response.json())
    .then(contents => {

                const hero = contents.find(
                    content => content.section === "hero"
                );

                if (hero) {

                    const heroTitle = document.getElementById("hero-title");
                    const heroDescription = document.getElementById("hero-description");

                    if (heroTitle && hero.title) {

                        const words = hero.title.trim().split(/\s+/);

                        heroTitle.innerHTML =
                            words[0] + "<br>" +
                            words.slice(1, -1).join(" ") +
                            " " +
                            `<span>${words[words.length - 1]}</span>`;
                    }

                    if (heroDescription && hero.description) {
                        heroDescription.textContent = hero.description;
                    }

                }


        // =========================
        // STORY
        // =========================

        const story = contents.find(
            content => content.section === "story"
        );

        if (story) {

            const storyTitle = document.getElementById("story-title");
            const storyDescription = document.getElementById("story-description");

            if (storyTitle && story.title) {
                storyTitle.textContent = story.title;
            }

            if (storyDescription && story.description) {
                storyDescription.textContent = story.description;
            }
        }


        // =========================
        // SPACE
        // =========================

        const space = contents.find(
            content => content.section === "space"
        );

        if (space) {

            const spaceTitle = document.getElementById("space-title");
            const spaceDescription = document.getElementById("space-description");

            const spaceImage = document.getElementById("space-image");
            const spaceImage2 = document.getElementById("space-image-2");
            const spaceImage3 = document.getElementById("space-image-3");


            if (spaceTitle && space.title) {
                spaceTitle.textContent = space.title;
            }

            if (spaceDescription && space.description) {
                spaceDescription.textContent = space.description;
            }


            if (spaceImage && space.image) {
                spaceImage.src = space.image;
            }

            if (spaceImage2 && space.image_2) {
                spaceImage2.src = space.image_2;
            }

            if (spaceImage3 && space.image_3) {
                spaceImage3.src = space.image_3;
            }
        }

        // VISIT CONTENT

        const visit = contents.find(content => content.section === "visit");

        if (visit) {

            const visitTitle = document.querySelector(".visit-title");
            const visitDescription = document.querySelector(".visit-description");

            const visitAddress = document.getElementById("visit-address");

            const visitOpeningHours = document.getElementById("visit-opening-hours");

             const visitPhone = document.getElementById("visit-phone");
            const visitWhatsapp = document.getElementById("visit-whatsapp");
            const visitLocationName = document.getElementById("visit-location-name");
            const visitMapLink = document.getElementById("visit-map-link");

            if (visitOpeningHours && visit.opening_hours) {
                visitOpeningHours.textContent = visit.opening_hours;
            }


            if (visitTitle && visit.title) {
                visitTitle.textContent = visit.title;
            }

            if (visitDescription && visit.description) {
                visitDescription.textContent = visit.description;
            }

            if (visitAddress && visit.address) {
                visitAddress.textContent = visit.address;
            }

             // Phone
            if (visitPhone && visit.phone) {

                visitPhone.textContent = visit.phone;

                let phoneNumber = visit.phone.replace(/\D/g, "");

                if (phoneNumber.startsWith("0")) {
                    phoneNumber = "6" + phoneNumber;
                }

                visitPhone.href = "tel:+" + phoneNumber;
            }


            // WhatsApp
            if (visitWhatsapp && visit.whatsapp_link) {
                visitWhatsapp.href = visit.whatsapp_link;
            }


            // Location Name
            if (visitLocationName && visit.location_name) {
                visitLocationName.textContent = visit.location_name;
            }


            // Google Maps
            if (visitMapLink && visit.map_link) {
                visitMapLink.href = visit.map_link;
            }

        }

    })
    .catch(error => {
        console.error("Error fetching website content:", error);
    });


    fetch("../backend/api/content.php")
    .then(response => response.json())
    .then(contents => {

        // Hero
        // Story
        // Space
        // Visit

    })
    .catch(error => {
        console.error("Error fetching website content:", error);
    });


// =========================
// GALLERY
// =========================

fetch("../backend/api/gallery.php")
    .then(response => response.json())
    .then(result => {

        const galleryGrid = document.getElementById("gallery-grid");

        if (!galleryGrid) return;

        result.data.forEach(item => {

            const galleryItem = document.createElement("div");
            galleryItem.classList.add("gallery-item");

            const img = document.createElement("img");

            img.src = item.image;
            img.alt = item.title || "Lexzo Coffee";

            galleryItem.appendChild(img);
            galleryGrid.appendChild(galleryItem);

        });

    })
    .catch(error => {
        console.error("Error fetching gallery:", error);
    });


    // =========================
// HOMEPAGE MENU CATEGORY TABS
// =========================

document.addEventListener("DOMContentLoaded", function () {

    const categoryTabs = document.querySelectorAll(".menu-category");
    const categoryContents = document.querySelectorAll(".menu-category-content");

    categoryTabs.forEach(tab => {

        tab.addEventListener("click", function (event) {

            event.preventDefault();

            // Remove active from all tabs
            categoryTabs.forEach(item => {
                item.classList.remove("active");
            });

            // Add active to clicked tab
            this.classList.add("active");

            // Hide all category contents
            categoryContents.forEach(content => {
                content.classList.remove("active");
            });

            // Get target category
            const targetId = this.getAttribute("href");

            const targetContent = document.querySelector(targetId);

            if (targetContent) {
                targetContent.classList.add("active");
            }

            // Update URL hash
            history.replaceState(null, null, targetId);

        });

    });

});

