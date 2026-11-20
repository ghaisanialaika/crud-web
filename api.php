<?php
/**
 * ============================================================================
 * API CRUD CATATAN STOK (api.php)
 * ============================================================================
 * File backend API yang melayani permintaan AJAX JSON dari frontend.
 * Menghitung metrik inventaris nyata (total jenis, total unit, stok aman/menipis/habis,
 * dan distribusi kategori).
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function sendJsonResponse(int $statusCode, array $payload): never {
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$pdo = getDatabaseConnection();
if (!$pdo) {
    sendJsonResponse(500, [
        'success' => false,
        'message' => 'Gagal terhubung ke database MySQL. Pastikan MySQL XAMPP aktif dan database "catatan_stok" sudah diimpor.'
    ]);
}

$action = (string)($_GET['action'] ?? '');
$method = $_SERVER['REQUEST_METHOD'];

try {
    // -------------------------------------------------------------------------
    // READ: Ambil semua data barang & ringkasan statistik stok
    // -------------------------------------------------------------------------
    if ($action === 'list' && $method === 'GET') {
        $stmt = $pdo->query('SELECT id, name, category, stock, price, created_at, updated_at FROM items ORDER BY id DESC');
        $items = $stmt->fetchAll() ?: [];

        $totalItems = count($items);
        $totalStock = 0;
        $totalAssetValue = 0;
        $categoryItemCounts = [];
        $categoryStockCounts = [];
        $stockSafe = 0;      // Stok > 10
        $stockWarning = 0;   // Stok 1 - 10
        $stockEmpty = 0;     // Stok = 0

        foreach ($items as $item) {
            $stock = (int)$item['stock'];
            $price = (int)$item['price'];
            $cat   = (string)$item['category'];

            $totalStock += $stock;
            $totalAssetValue += ($stock * $price);

            $categoryItemCounts[$cat]  = ($categoryItemCounts[$cat] ?? 0) + 1;
            $categoryStockCounts[$cat] = ($categoryStockCounts[$cat] ?? 0) + $stock;

            if ($stock > 10) {
                $stockSafe++;
            } elseif ($stock > 0) {
                $stockWarning++;
            } else {
                $stockEmpty++;
            }
        }

        // Rata-rata ketersediaan stok
        $safePercentage = $totalItems > 0 ? (int)round(($stockSafe / $totalItems) * 100) : 0;

        sendJsonResponse(200, [
            'success' => true,
            'data' => $items,
            'summary' => [
                'total_items'          => $totalItems,
                'total_stock'          => $totalStock,
                'total_categories'     => count($categoryItemCounts),
                'total_asset_value'    => $totalAssetValue,
                'safe_percentage'      => $safePercentage,
                'category_item_counts' => $categoryItemCounts,
                'category_stock_counts'=> $categoryStockCounts,
                'stock_status'         => [
                    'safe'    => $stockSafe,
                    'warning' => $stockWarning,
                    'empty'   => $stockEmpty,
                ],
            ]
        ]);
    }

    // -------------------------------------------------------------------------
    // CREATE & UPDATE: Tambah atau Ubah Barang
    // -------------------------------------------------------------------------
    if (in_array($action, ['create', 'update'], true) && in_array($method, ['POST', 'PUT'], true)) {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        if (!is_array($input)) {
            $input = $_POST;
        }

        $name     = trim((string)($input['name'] ?? ''));
        $category = trim((string)($input['category'] ?? ''));
        $stock    = filter_var($input['stock'] ?? null, FILTER_VALIDATE_INT);
        $price    = filter_var($input['price'] ?? null, FILTER_VALIDATE_INT);

        if ($name === '' || mb_strlen($name) > 100) {
            sendJsonResponse(422, [
                'success' => false,
                'message' => 'Nama barang wajib diisi (maksimal 100 karakter).'
            ]);
        }

        if ($category === '' || mb_strlen($category) > 60) {
            sendJsonResponse(422, [
                'success' => false,
                'message' => 'Kategori wajib diisi (maksimal 60 karakter).'
            ]);
        }

        if ($stock === false || $stock < 0) {
            sendJsonResponse(422, [
                'success' => false,
                'message' => 'Jumlah stok harus berupa angka bulat 0 atau lebih.'
            ]);
        }

        if ($price === false || $price < 0) {
            sendJsonResponse(422, [
                'success' => false,
                'message' => 'Harga satuan harus berupa angka bulat 0 atau lebih.'
            ]);
        }

        if ($action === 'create') {
            $stmt = $pdo->prepare('INSERT INTO items (name, category, stock, price) VALUES (:name, :category, :stock, :price)');
            $stmt->execute([
                ':name'     => $name,
                ':category' => $category,
                ':stock'    => $stock,
                ':price'    => $price,
            ]);

            sendJsonResponse(201, [
                'success' => true,
                'message' => 'Barang baru berhasil ditambahkan ke catatan stok!',
                'id'      => (int)$pdo->lastInsertId(),
            ]);
        }

        if ($action === 'update') {
            $id = filter_var($_GET['id'] ?? ($input['id'] ?? null), FILTER_VALIDATE_INT);
            if (!$id || $id < 1) {
                sendJsonResponse(400, [
                    'success' => false,
                    'message' => 'ID barang tidak valid.'
                ]);
            }

            $stmt = $pdo->prepare('UPDATE items SET name = :name, category = :category, stock = :stock, price = :price WHERE id = :id');
            $stmt->execute([
                ':name'     => $name,
                ':category' => $category,
                ':stock'    => $stock,
                ':price'    => $price,
                ':id'       => $id,
            ]);

            sendJsonResponse(200, [
                'success' => true,
                'message' => 'Perubahan data barang berhasil disimpan!',
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // DELETE: Hapus Barang
    // -------------------------------------------------------------------------
    if ($action === 'delete' && ($method === 'DELETE' || $method === 'POST')) {
        $id = filter_var($_GET['id'] ?? ($_POST['id'] ?? null), FILTER_VALIDATE_INT);
        if (!$id || $id < 1) {
            sendJsonResponse(400, [
                'success' => false,
                'message' => 'ID barang tidak valid.'
            ]);
        }

        $stmt = $pdo->prepare('DELETE FROM items WHERE id = :id');
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            sendJsonResponse(404, [
                'success' => false,
                'message' => 'Barang tidak ditemukan atau sudah dihapus.'
            ]);
        }

        sendJsonResponse(200, [
            'success' => true,
            'message' => 'Barang berhasil dihapus dari catatan stok.'
        ]);
    }

    sendJsonResponse(404, [
        'success' => false,
        'message' => 'Aksi API tidak ditemukan.'
    ]);

} catch (PDOException $e) {
    error_log('Database Error: ' . $e->getMessage());
    sendJsonResponse(500, [
        'success' => false,
        'message' => 'Terjadi masalah pada database saat memproses data.'
    ]);
} catch (Throwable $e) {
    error_log('Server Error: ' . $e->getMessage());
    sendJsonResponse(500, [
        'success' => false,
        'message' => 'Terjadi kesalahan sistem di server.'
    ]);
}
