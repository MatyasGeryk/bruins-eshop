<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

if (Cart::isEmpty()) redirect('/kosik.php');
if (empty($_SESSION['order']['address'])) redirect('/objednavka-1.php');

$shippingRepo = new ShippingMethodRepository();
$paymentRepo = new PaymentMethodRepository();
$shippingList = $shippingRepo->getAll();
$paymentList = $paymentRepo->getAll();

$errors = [];
$selectedShipping = $_SESSION['order']['shipping'] ?? '';
$selectedPayment  = $_SESSION['order']['payment'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedShipping = (string)($_POST['shipping'] ?? '');
    $selectedPayment  = (string)($_POST['payment'] ?? '');

    $allowedShipping = array_map(fn($s) => $s->code, $shippingList);
    $allowedPayment  = array_map(fn($p) => $p->code, $paymentList);

    $v = new Validator();
    $v->required('shipping', $selectedShipping, 'Vyberte způsob dopravy.')
      ->in('shipping', $selectedShipping, $allowedShipping, 'Neplatný způsob dopravy.')
      ->required('payment', $selectedPayment, 'Vyberte způsob platby.')
      ->in('payment', $selectedPayment, $allowedPayment, 'Neplatný způsob platby.');

    if ($v->isValid()) {
        $_SESSION['order']['shipping'] = $selectedShipping;
        $_SESSION['order']['payment']  = $selectedPayment;
        redirect('/objednavka-3.php');
    }
    $errors = $v->getErrors();
}

$pageTitle = 'Objednávka – krok 2 — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="checkout-section">
    <div class="container">
        <h1 class="checkout-title">Dokončení objednávky</h1>

        <div class="checkout-steps">
            <div class="step completed"><div class="step-number">✓</div><div class="step-label">Dodací adresa</div></div>
            <div class="step-line completed"></div>
            <div class="step active"><div class="step-number">2</div><div class="step-label">Doprava a platba</div></div>
            <div class="step-line"></div>
            <div class="step"><div class="step-number">3</div><div class="step-label">Shrnutí</div></div>
        </div>

        <div class="checkout-container">
            <div class="checkout-form">
                <form method="post" action="/objednavka-2.php">
                    <h2>Způsob dopravy</h2>
                    <?php if (isset($errors['shipping'])): ?>
                        <p class="error" style="color:#ef4444;"><?= e($errors['shipping']) ?></p>
                    <?php endif; ?>
                    <div class="shipping-options">
                        <?php foreach ($shippingList as $s): ?>
                            <label class="shipping-option">
                                <input type="radio" name="shipping" value="<?= e($s->code) ?>" <?= $selectedShipping === $s->code ? 'checked' : '' ?>>
                                <div class="option-content">
                                    <div class="option-header">
                                        <div class="option-info">
                                            <strong><?= e($s->name) ?></strong>
                                            <p><?= e($s->description) ?> — <?= e($s->deliveryTime) ?></p>
                                        </div>
                                    </div>
                                    <div class="option-price"><?= e(price($s->price)) ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <h2 style="margin-top:32px;">Způsob platby</h2>
                    <?php if (isset($errors['payment'])): ?>
                        <p class="error" style="color:#ef4444;"><?= e($errors['payment']) ?></p>
                    <?php endif; ?>
                    <div class="shipping-options">
                        <?php foreach ($paymentList as $p): ?>
                            <label class="shipping-option">
                                <input type="radio" name="payment" value="<?= e($p->code) ?>" <?= $selectedPayment === $p->code ? 'checked' : '' ?>>
                                <div class="option-content">
                                    <div class="option-header">
                                        <div class="option-info">
                                            <strong><?= e($p->name) ?></strong>
                                            <p><?= e($p->description) ?></p>
                                        </div>
                                    </div>
                                    <div class="option-price"><?= e(price($p->fee)) ?></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div style="display:flex;justify-content:space-between;margin-top:24px;">
                        <a href="/objednavka-1.php" class="btn-secondary">← Zpět</a>
                        <button type="submit" class="btn-primary">Pokračovat →</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
