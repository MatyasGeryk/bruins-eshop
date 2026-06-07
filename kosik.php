<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

// POST akce s PRG patternem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'add') {
        $productId = (int)($_POST['product_id'] ?? 0);
        $variantId = isset($_POST['variant_id']) && $_POST['variant_id'] !== '' ? (int)$_POST['variant_id'] : null;
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));
        if ($productId > 0) {
            Cart::add($productId, $variantId, $quantity);
        }
    } elseif ($action === 'update') {
        $key = (string)($_POST['key'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 1);
        if ($key !== '') Cart::update($key, $quantity);
    } elseif ($action === 'remove') {
        $key = (string)($_POST['key'] ?? '');
        if ($key !== '') Cart::remove($key);
    }
    redirect('/kosik.php');
}

$items = Cart::detailed();
$total = Cart::itemsTotal();

$pageTitle = 'Košík — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="/index.php">Domů</a> / Košík</div>
        <h1>Váš košík</h1>
    </div>
</section>

<section class="checkout-section">
    <div class="container">
        <?php if ($items === []): ?>
            <div style="text-align:center;padding:60px 0;">
                <p style="font-size:18px;color:#4a4a4a;margin-bottom:24px;">Váš košík je prázdný.</p>
                <a href="/kategorie.php" class="btn-primary">Pokračovat v nákupu</a>
            </div>
        <?php else: ?>
            <div class="checkout-container">
                <div class="checkout-form">
                    <h2>Položky v košíku</h2>
                    <?php foreach ($items as $row): ?>
                        <?php /** @var ProductDTO $p */ $p = $row['product']; /** @var ProductVariantDTO|null $v */ $v = $row['variant']; ?>
                        <div class="review-item" style="display:flex;gap:16px;align-items:center;border-bottom:1px solid #e5e5e5;padding:16px 0;">
                            <div style="flex:1;">
                                <h4 style="margin:0;"><a href="/produkt.php?slug=<?= e($p->slug) ?>" style="color:inherit;"><?= e($p->name) ?></a></h4>
                                <?php if ($v !== null): ?>
                                    <p class="item-specs" style="color:#4a4a4a;font-size:14px;"><?= e($v->name) ?>: <?= e($v->value) ?></p>
                                <?php endif; ?>
                                <p style="color:#4a4a4a;font-size:14px;">Cena za kus: <?= e(price((float)$row['unit_price'])) ?></p>
                            </div>
                            <form method="post" action="/kosik.php" style="display:flex;gap:6px;align-items:center;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="key" value="<?= e((string)$row['key']) ?>">
                                <input type="number" name="quantity" value="<?= (int)$row['quantity'] ?>" min="1" max="50" style="width:70px;padding:6px;">
                                <button type="submit" class="btn-secondary">Změnit</button>
                            </form>
                            <div style="min-width:120px;text-align:right;font-weight:700;"><?= e(price((float)$row['subtotal'])) ?></div>
                            <form method="post" action="/kosik.php">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="key" value="<?= e((string)$row['key']) ?>">
                                <button type="submit" class="btn-secondary" style="color:#ef4444;">×</button>
                            </form>
                        </div>
                    <?php endforeach; ?>

                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:24px;">
                        <a href="/kategorie.php" class="btn-secondary">← Pokračovat v nákupu</a>
                        <a href="/objednavka-1.php" class="btn-primary">Pokračovat k objednávce →</a>
                    </div>
                </div>

                <aside class="order-summary">
                    <h3>Souhrn</h3>
                    <div class="summary-row"><span>Mezisoučet</span><span><?= e(price($total)) ?></span></div>
                    <p style="font-size:13px;color:#4a4a4a;margin-top:12px;">Doprava a platba se přidá v dalším kroku.</p>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
