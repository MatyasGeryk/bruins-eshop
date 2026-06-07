<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$query = trim((string)($_GET['q'] ?? ''));
$results = [];
if ($query !== '') {
    $productRepo = new ProductRepository();
    $results = $productRepo->search($query);
}

$pageTitle = 'Vyhledávání — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="/index.php">Domů</a> / Vyhledávání</div>
        <h1>Výsledky vyhledávání</h1>
        <?php if ($query !== ''): ?>
            <p>Výsledky pro: <strong style="color:#FFB81C;">"<?= e($query) ?>"</strong></p>
        <?php endif; ?>
    </div>
</section>

<section class="products-listing">
    <div class="container">
        <form action="/vyhledavani.php" method="get" style="margin-bottom:24px;display:flex;gap:8px;">
            <input type="search" name="q" value="<?= e($query) ?>" placeholder="Hledat produkty..." style="flex:1;padding:10px;border:1px solid #e5e5e5;border-radius:4px;">
            <button type="submit" class="btn-primary">Hledat</button>
        </form>

        <?php if ($query === ''): ?>
            <p>Zadejte hledaný výraz.</p>
        <?php elseif ($results === []): ?>
            <p>Pro výraz "<strong><?= e($query) ?></strong>" nebyly nalezeny žádné produkty.</p>
        <?php else: ?>
            <p class="products-count">Nalezeno <?= count($results) ?> produktů</p>
            <div class="products-grid-listing">
                <?php foreach ($results as $product): ?>
                    <?php require __DIR__ . '/partials/product-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
