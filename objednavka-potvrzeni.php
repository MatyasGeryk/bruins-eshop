<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

if (empty($_SESSION['last_order'])) redirect('/index.php');
$order = $_SESSION['last_order'];

$pageTitle = 'Potvrzení objednávky — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="confirmation-section">
    <div class="container">
        <div class="confirmation-container">
            <div class="success-icon">
                <div class="checkmark-circle"><div class="checkmark">✓</div></div>
            </div>

            <h1 class="confirmation-title">Objednávka byla odeslána!</h1>
            <p class="confirmation-subtitle">Děkujeme za nákup v Boston Bruins Fan Shopu</p>

            <div class="order-number-box">
                <p class="order-number-label">Číslo objednávky</p>
                <p class="order-number">#<?= e($order['order_number']) ?></p>
            </div>

            <div class="confirmation-message">
                <p>Na e-mail <strong><?= e($order['address']['email']) ?></strong> jsme odeslali potvrzení objednávky.</p>
                <p>Platební instrukce a sledovací číslo zásilky najdete v e-mailu.</p>
            </div>

            <div class="order-summary-confirmation">
                <h2>Shrnutí objednávky</h2>

                <div class="summary-section">
                    <h3>Objednané produkty</h3>
                    <?php foreach ($order['items'] as $it): ?>
                        <div class="confirmation-item" style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #e5e5e5;">
                            <div>
                                <h4 style="margin:0;"><?= e($it['name']) ?></h4>
                                <?php if (!empty($it['variant'])): ?><p style="color:#4a4a4a;font-size:14px;"><?= e($it['variant']) ?></p><?php endif; ?>
                                <p style="color:#4a4a4a;font-size:14px;">Množství: <?= (int)$it['quantity'] ?> ks</p>
                            </div>
                            <div style="font-weight:700;"><?= e(price((float)$it['subtotal'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="summary-section" style="margin-top:24px;">
                    <div class="summary-row"><span>Mezisoučet</span><span><?= e(price((float)$order['items_total'])) ?></span></div>
                    <div class="summary-row"><span>Doprava (<?= e($order['shipping']['name']) ?>)</span><span><?= e(price((float)$order['shipping']['price'])) ?></span></div>
                    <div class="summary-row"><span>Platba (<?= e($order['payment']['name']) ?>)</span><span><?= e(price((float)$order['payment']['fee'])) ?></span></div>
                    <div class="summary-row" style="font-size:20px;font-weight:700;margin-top:8px;border-top:2px solid #0a0a0a;padding-top:8px;"><span>Celkem</span><span><?= e(price((float)$order['total'])) ?></span></div>
                </div>
            </div>

            <div style="text-align:center;margin-top:32px;">
                <a href="/index.php" class="btn-primary">Zpět na hlavní stránku</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
