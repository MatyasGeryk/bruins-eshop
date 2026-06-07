<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$productRepo = new ProductRepository();
$categoryRepo = new CategoryRepository();

$featured = $productRepo->getFeatured(4);
$categories = $categoryRepo->getAll();

$pageTitle = 'Boston Bruins Fan Shop — Oficiální merchandise';
$pageDescription = 'Oficiální fan shop Boston Bruins. Dresy, čepice, doplňky a hokejové vybavení s rychlým doručením po ČR a SK.';
require __DIR__ . '/partials/header.php';
?>

<section class="hero-banner">
    <div class="container">
        <div class="hero-content">
            <span class="eyebrow">Sezóna 2024 / 2025</span>
            <h2>Hraj jako <span class="accent">Bruins.</span><br>Fan'di jako Boston.</h2>
            <h3>Oficiální merchandise Boston Bruins pro české a slovenské fanoušky. Dresy, doplňky a sběratelské kousky přímo z TD Garden.</h3>
            <a href="/kategorie.php" class="btn-primary">Prohlédnout kolekci →</a>
        </div>
        <div class="hero-image">
            <img src="https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7" alt="Boston Bruins dres Pastrňák 88">
        </div>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2 class="section-title">Doporučené produkty</h2>
        <p class="section-subtitle">Nejprodávanější kousky této sezóny</p>
        <div class="products-grid">
            <?php foreach ($featured as $product): ?>
                <?php require __DIR__ . '/partials/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="featured-products">
    <div class="container">
        <h2 class="section-title">Kategorie</h2>
        <p class="section-subtitle">Prozkoumejte celou nabídku</p>
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
