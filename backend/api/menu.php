<?php

require_once "../config/database.php";

header("Content-Type: application/json");

$sql = "
    SELECT
        id,
        category_id,
        name,
        description,
        price,
        price_type,
        image,
        sort_order
    FROM menu_items
    WHERE status = 1
    ORDER BY category_id, sort_order
";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($menuItems);