<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';
http_response_code(404);

$pageTitle = '404 — Stránka nenalezena';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header" style="text-align:center;padding:80px 0;">
    <div class="container">
        <h1 style="font-size:120px;font-family:'Bebas Neue',sans-serif;color:#FFB81C;letter-spacing:8px;">404</h1>
        <h2 style="margin-top:8px;">Stránka nebyla nalezena</h2>
        <p style="margin-top:12px;color:#4a4a4a;">Hledaná stránka, produkt nebo kategorie neexistuje nebo byla přesunuta.</p>
        <div style="margin-top:32px;display:flex;gap:12px;justify-content:center;">
            <a href="/index.php" class="btn-primary">Zpět na hlavní stránku</a>
            <a href="/kategorie.php" class="btn-secondary">Prohlédnout kategorie</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
