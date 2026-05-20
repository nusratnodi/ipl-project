<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

function read_json_body(): array {
    $raw = file_get_contents('php://input');
    if (!$raw) return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function respond($payload, int $status = 200): void {
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

try {
    if ($method === 'GET' && $action === 'list') {
        $stmt = $pdo->query('SELECT id, name, price, description, created_at FROM products ORDER BY id DESC');
        respond(['ok' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'GET' && $action === 'get') {
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT id, name, price, description, created_at FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) respond(['ok' => false, 'error' => 'Not found'], 404);
        respond(['ok' => true, 'data' => $row]);
    }

    if ($method === 'POST' && $action === 'create') {
        $body = read_json_body();
        $name = trim($body['name'] ?? '');
        $price = (float)($body['price'] ?? 0);
        $description = trim($body['description'] ?? '');

        if ($name === '') respond(['ok' => false, 'error' => 'Name is required'], 400);

        $stmt = $pdo->prepare('INSERT INTO products (name, price, description) VALUES (?, ?, ?)');
        $stmt->execute([$name, $price, $description]);
        respond(['ok' => true, 'id' => (int)$pdo->lastInsertId()]);
    }

    if ($method === 'POST' && $action === 'update') {
        $body = read_json_body();
        $id = (int)($body['id'] ?? 0);
        $name = trim($body['name'] ?? '');
        $price = (float)($body['price'] ?? 0);
        $description = trim($body['description'] ?? '');

        if ($id <= 0) respond(['ok' => false, 'error' => 'Invalid id'], 400);
        if ($name === '') respond(['ok' => false, 'error' => 'Name is required'], 400);

        $stmt = $pdo->prepare('UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?');
        $stmt->execute([$name, $price, $description, $id]);
        respond(['ok' => true, 'updated' => $stmt->rowCount()]);
    }

    if ($method === 'POST' && $action === 'delete') {
        $body = read_json_body();
        $id = (int)($body['id'] ?? 0);
        if ($id <= 0) respond(['ok' => false, 'error' => 'Invalid id'], 400);

        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        respond(['ok' => true, 'deleted' => $stmt->rowCount()]);
    }

    respond(['ok' => false, 'error' => 'Unknown action or method'], 400);

} catch (PDOException $e) {
    respond(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
}
