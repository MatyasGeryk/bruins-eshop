<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

if (Cart::isEmpty()) redirect('/kosik.php');
if (empty($_SESSION['order']['address'])) redirect('/objednavka-1.php');
if (empty($_SESSION['order']['shipping']) || empty($_SESSION['order']['payment'])) redirect('/objednavka-2.php');

$shippingRepo = new ShippingMethodRepository();
$paymentRepo  = new PaymentMethodRepository();
$customerRepo = new CustomerRepository();
$orderRepo    = new OrderRepository();

$address = $_SESSION['order']['address'];
$shipping = $shippingRepo->getByCode($_SESSION['order']['shipping']);
$payment  = $paymentRepo->getByCode($_SESSION['order']['payment']);

if ($shipping === null || $payment === null) redirect('/objednavka-2.php');

$items = Cart::detailed();
$itemsTotal = Cart::itemsTotal();
$total = $itemsTotal + $shipping->price + $payment->fee;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agree = isset($_POST['agree']) ? 'yes' : '';
    $v = new Validator();
    $v->required('agree', $agree, 'Musíte souhlasit s obchodními podmínkami.');

    if ($v->isValid()) {
        $customerId = $customerRepo->create($address);
        $orderNumber = OrderRepository::generateOrderNumber();
        $orderRepo->create(
            $customerId, $orderNumber,
            $shipping->id, $payment->id,
            $itemsTotal, $shipping->price, $payment->fee, $total,
            $items
        );

        $_SESSION['last_order'] = [
            'order_number' => $orderNumber,
            'address'      => $address,
            'shipping'     => ['name' => $shipping->name, 'price' => $shipping->price],
            'payment'      => ['name' => $payment->name, 'fee' => $payment->fee],
            'items'        => array_map(fn($r) => [
                'name'       => $r['product']->name,
                'variant'    => $r['variant'] ? $r['variant']->name . ': ' . $r['variant']->value : null,
                'quantity'   => $r['quantity'],
                'unit_price' => $r['unit_price'],
                'subtotal'   => $r['subtotal'],
            ], $items),
            'items_total'  => $itemsTotal,
            'total'        => $total,
        ];

        Cart::clear();
        unset($_SESSION['order']);
        redirect('/objednavka-potvrzeni.php');
    }
    $errors = $v->getErrors();
}

$pageTitle = 'Objednávka – shrnutí — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="checkout-section">
    <div class="container">
        <h1 class="checkout-title">Dokončení objednávky</h1>

        <div class="checkout-steps">
            <div class="step completed"><div class="step-number">✓</div><div class="step-label">Dodací adresa</div></div>
            <div class="step-line completed"></div>
            <div class="step completed"><div class="step-number">✓</div><div class="step-label">Doprava a platba</div></div>
            <div class="step-line completed"></div>
            <div class="step active"><div class="step-number">3</div><div class="step-label">Shrnutí</div></div>
        </div>

        <div class="checkout-container">
            <div class="checkout-form">
                <h2>Kontrola objednávky</h2>

                <div class="review-section">
                    <h3>Dodací adresa</h3>
                    <p><?= e($address['first_name'] . ' ' . $address['last_name']) ?></p>
                    <p><?= e($address['street']) ?>, <?= e($address['zip']) ?> <?= e($address['city']) ?></p>
                    <p>E-mail: <?= e($address['email']) ?> | Telefon: <?= e($address['phone']) ?></p>
                    <?php if ($address['note'] !== ''): ?><p>Poznámka: <?= e($address['note']) ?></p><?php endif; ?>
                </div>

                <div class="review-section" style="margin-top:24px;">
                    <h3>Doprava a platba</h3>
                    <p>Doprava: <strong><?= e($shipping->name) ?></strong> (<?= e(price($shipping->price)) ?>)</p>
                    <p>Platba: <strong><?= e($payment->name) ?></strong> (<?= e(price($payment->fee)) ?>)</p>
                </div>

                <div class="review-section" style="margin-top:24px;">
                    <h3>Objednané položky</h3>
                    <?php foreach ($items as $row): ?>
                        <?php /** @var ProductDTO $p */ $p = $row['product']; /** @var ProductVariantDTO|null $v */ $v = $row['variant']; ?>
                        <div class="review-item" style="display:flex;gap:16px;padding:12px 0;border-bottom:1px solid #e5e5e5;">
                            <div style="flex:1;">
                                <h4 style="margin:0;"><?= e($p->name) ?></h4>
                                <?php if ($v !== null): ?><p style="color:#4a4a4a;font-size:14px;"><?= e($v->name) ?>: <?= e($v->value) ?></p><?php endif; ?>
                                <p style="color:#4a4a4a;font-size:14px;">Množství: <?= (int)$row['quantity'] ?> ks × <?= e(price((float)$row['unit_price'])) ?></p>
                            </div>
                            <div style="font-weight:700;"><?= e(price((float)$row['subtotal'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:24px;padding-top:16px;border-top:2px solid #0a0a0a;">
                    <div class="summary-row"><span>Mezisoučet</span><span><?= e(price($itemsTotal)) ?></span></div>
                    <div class="summary-row"><span>Doprava</span><span><?= e(price($shipping->price)) ?></span></div>
                    <div class="summary-row"><span>Platba</span><span><?= e(price($payment->fee)) ?></span></div>
                    <div class="summary-row" style="font-size:20px;font-weight:700;margin-top:8px;"><span>Celkem</span><span><?= e(price($total)) ?></span></div>
                </div>

                <form method="post" action="/objednavka-3.php" style="margin-top:24px;">
                    <label style="display:flex;gap:8px;align-items:flex-start;">
                        <input type="checkbox" name="agree" value="yes">
                        <span>Souhlasím s obchodními podmínkami a zpracováním osobních údajů. *</span>
                    </label>
                    <?php if (isset($errors['agree'])): ?>
                        <p class="error" style="color:#ef4444;margin-top:8px;"><?= e($errors['agree']) ?></p>
                    <?php endif; ?>

                    <div style="display:flex;justify-content:space-between;margin-top:24px;">
                        <a href="/objednavka-2.php" class="btn-secondary">← Zpět</a>
                        <button type="submit" class="btn-primary">Odeslat objednávku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
