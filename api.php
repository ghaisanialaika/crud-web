<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function respond(int $status, array $payload): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=catatan_stok;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $error) {
    respond(500, ['success' => false, 'message' => 'Database belum terhubung. Pastikan MySQL XAMPP aktif dan database catatan_stok sudah diimpor.']);
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
try {
    if ($action === 'list' && $method === 'GET') {
        $rows = $pdo->query('SELECT id, name, category, stock, price FROM items ORDER BY id DESC')->fetchAll();
        respond(200, ['success' => true, 'data' => $rows]);
    }
    if (in_array($action, ['create', 'update'], true) && in_array($method, ['POST', 'PUT'], true)) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) respond(400, ['success' => false, 'message' => 'Data yang dikirim tidak valid.']);
        $name = trim((string)($input['name'] ?? '')); $category = trim((string)($input['category'] ?? ''));
        $stock = filter_var($input['stock'] ?? null, FILTER_VALIDATE_INT); $price = filter_var($input['price'] ?? null, FILTER_VALIDATE_INT);
        if ($name === '' || $category === '' || mb_strlen($name) > 100 || mb_strlen($category) > 60) respond(422, ['success' => false, 'message' => 'Nama barang dan kategori wajib diisi (maksimal 100 dan 60 karakter).']);
        if ($stock === false || $stock < 0 || $price === false || $price < 0) respond(422, ['success' => false, 'message' => 'Stok dan harga harus berupa angka bulat nol atau lebih.']);
        if ($action === 'create' && $method === 'POST') {
            $stmt = $pdo->prepare('INSERT INTO items (name, category, stock, price) VALUES (?, ?, ?, ?)'); $stmt->execute([$name, $category, $stock, $price]);
            respond(201, ['success' => true, 'message' => 'Barang berhasil ditambahkan.']);
        }
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) respond(400, ['success' => false, 'message' => 'ID barang tidak valid.']);
        $stmt = $pdo->prepare('UPDATE items SET name = ?, category = ?, stock = ?, price = ? WHERE id = ?'); $stmt->execute([$name, $category, $stock, $price, $id]);
        if (!$stmt->rowCount()) { $check = $pdo->prepare('SELECT id FROM items WHERE id = ?'); $check->execute([$id]); if (!$check->fetch()) respond(404, ['success' => false, 'message' => 'Barang tidak ditemukan.']); }
        respond(200, ['success' => true, 'message' => 'Perubahan barang berhasil disimpan.']);
    }
    if ($action === 'delete' && $method === 'DELETE') {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id || $id < 1) respond(400, ['success' => false, 'message' => 'ID barang tidak valid.']);
        $stmt = $pdo->prepare('DELETE FROM items WHERE id = ?'); $stmt->execute([$id]);
        if (!$stmt->rowCount()) respond(404, ['success' => false, 'message' => 'Barang tidak ditemukan.']);
        respond(200, ['success' => true, 'message' => 'Barang berhasil dihapus.']);
    }
    respond(404, ['success' => false, 'message' => 'Aksi tidak ditemukan.']);
} catch (PDOException $error) {
    respond(500, ['success' => false, 'message' => 'Terjadi masalah saat menyimpan data.']);
} catch (Throwable $error) {
    respond(500, ['success' => false, 'message' => 'Terjadi kesalahan pada server.']);
}
