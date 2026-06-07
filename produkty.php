<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';
if ($slug === '') redirect('/kategorie.php');

$categoryRepo = new CategoryRepository();
$category = $categoryRepo->getBySlug($slug);
if ($category === null) {
    header('HTTP/1.0 404 Not Found');
    require __DIR__ . '/404.php';
    exit;
}

$productRepo = new ProductRepository();
$products = $productRepo->getByCategorySlug($slug);

$pageTitle = e($category->name) . ' — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="/index.php">Domů</a> / <a href="/kategorie.php">Kategorie</a> / <?= e($category->name) ?>
        </div>
        <h1><?= e($category->name) ?></h1>
        <p><?= e($category->description) ?></p>
    </div>
</section>

<section class="products-listing">
    <div class="container">
        <div class="products-header">
            <p class="products-count">Zobrazeno <?= count($products) ?> produktů</p>
        </div>

        <?php if ($products === []): ?>
            <p>V této kategorii momentálně nejsou žádné produkty.</p>
        <?php else: ?>
            <div class="products-grid-listing">
                <?php foreach ($products as $product): ?>
                    <?php require __DIR__ . '/partials/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
