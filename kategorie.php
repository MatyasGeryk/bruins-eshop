<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$categoryRepo = new CategoryRepository();
$categories = $categoryRepo->getAll();

$pageTitle = 'Kategorie — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="/index.php">Domů</a> / Kategorie</div>
        <h1>Všechny kategorie</h1>
        <p>Vyberte si z naší kompletní nabídky oficiálního merchandise Boston Bruins.</p>
    </div>
</section>

<section class="content-page">
    <div class="container">
        <div class="categories-grid-full">
            <?php foreach ($categories as $cat): ?>
                <a href="/produkty.php?slug=<?= e($cat->slug) ?>" class="category-tile">
                    <div class="category-tile-content">
                        <h3><?= e($cat->name) ?> <span style="color:#FFB81C;">(<?= $categoryRepo->countProducts($cat->id) ?>)</span></h3>
                        <p><?= e($cat->description) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
