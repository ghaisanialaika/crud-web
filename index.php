<?php
/**
 * ============================================================================
 * HALAMAN UTAMA: CATATAN STOK (index.php)
 * ============================================================================
 * Aplikasi Catatan Stok Sederhana dengan desain modern terinspirasi Cognify.
 * Data dihitung secara realtime dari database MySQL dan dirender secara modular.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$pdo = getDatabaseConnection();
$items = [];
$summary = [
    'total_items'           => 0,
    'total_stock'           => 0,
    'total_categories'      => 0,
    'total_asset_value'     => 0,
    'safe_percentage'       => 0,
    'category_item_counts'  => [],
    'category_stock_counts' => [],
    'stock_status'          => [
        'safe'    => 0,
        'warning' => 0,
        'empty'   => 0,
    ]
];

if ($pdo) {
    try {
        $stmt = $pdo->query('SELECT id, name, category, stock, price, created_at, updated_at FROM items ORDER BY id DESC');
        $items = $stmt->fetchAll() ?: [];

        $totalStock = 0;
        $totalAssetValue = 0;
        $categoryItemCounts = [];
        $categoryStockCounts = [];
        $safeCount = 0;
        $warningCount = 0;
        $emptyCount = 0;

        foreach ($items as $item) {
            $stock = (int)$item['stock'];
            $price = (int)$item['price'];
            $cat   = (string)$item['category'];

            $totalStock += $stock;
            $totalAssetValue += ($stock * $price);
            $categoryItemCounts[$cat]  = ($categoryItemCounts[$cat] ?? 0) + 1;
            $categoryStockCounts[$cat] = ($categoryStockCounts[$cat] ?? 0) + $stock;

            if ($stock > 10) {
                $safeCount++;
            } elseif ($stock > 0) {
                $warningCount++;
            } else {
                $emptyCount++;
            }
        }

        $totalItemsCount = count($items);
        $safePercentage = $totalItemsCount > 0 ? (int)round(($safeCount / $totalItemsCount) * 100) : 0;

        $summary = [
            'total_items'           => $totalItemsCount,
            'total_stock'           => $totalStock,
            'total_categories'      => count($categoryItemCounts),
            'total_asset_value'     => $totalAssetValue,
            'safe_percentage'       => $safePercentage,
            'category_item_counts'  => $categoryItemCounts,
            'category_stock_counts' => $categoryStockCounts,
            'stock_status'          => [
                'safe'    => $safeCount,
                'warning' => $warningCount,
                'empty'   => $emptyCount,
            ]
        ];
    } catch (PDOException $e) {
        error_log('Gagal memuat data awal index.php: ' . $e->getMessage());
    }
}

// -----------------------------------------------------------------------------
// RENDER TAMPILAN MODULAR
// -----------------------------------------------------------------------------
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/title_bar.php';
require_once __DIR__ . '/includes/stat_cards.php';
require_once __DIR__ . '/includes/overview_cards.php';
require_once __DIR__ . '/includes/inventory_table.php';
require_once __DIR__ . '/includes/footer.php';
