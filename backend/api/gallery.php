<?php

require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

try {

    $stmt = $pdo->query("
        SELECT
            id,
            title,
            image,
            sort_order
        FROM gallery
        WHERE status = 1
        ORDER BY sort_order ASC, id ASC
    ");

    $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $gallery
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Failed to load gallery.'
    ]);
}