<?php

require_once "../config/database.php";

header("Content-Type: application/json");

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
    ORDER BY id
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$content = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($content);

?>