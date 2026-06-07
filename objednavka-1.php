<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

if (Cart::isEmpty()) redirect('/kosik.php');

$errors = [];
$data = $_SESSION['order']['address'] ?? [
    'first_name' => '', 'last_name' => '', 'email' => '', 'phone' => '',
    'street' => '', 'city' => '', 'zip' => '', 'note' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => trim((string)($_POST['first_name'] ?? '')),
        'last_name'  => trim((string)($_POST['last_name'] ?? '')),
        'email'      => trim((string)($_POST['email'] ?? '')),
        'phone'      => trim((string)($_POST['phone'] ?? '')),
        'street'     => trim((string)($_POST['street'] ?? '')),
        'city'       => trim((string)($_POST['city'] ?? '')),
        'zip'        => trim((string)($_POST['zip'] ?? '')),
        'note'       => trim((string)($_POST['note'] ?? '')),
    ];

    $v = new Validator();
    $v->required('first_name', $data['first_name'], 'Jméno je povinné.')
      ->minLength('first_name', $data['first_name'], 2, 'Jméno musí mít alespoň 2 znaky.')
      ->maxLength('first_name', $data['first_name'], 50, 'Jméno je příliš dlouhé.')
      ->required('last_name', $data['last_name'], 'Příjmení je povinné.')
      ->minLength('last_name', $data['last_name'], 2, 'Příjmení musí mít alespoň 2 znaky.')
      ->maxLength('last_name', $data['last_name'], 50, 'Příjmení je příliš dlouhé.')
      ->required('email', $data['email'], 'E-mail je povinný.')
      ->email('email', $data['email'], 'Neplatný formát e-mailu.')
      ->maxLength('email', $data['email'], 100, 'E-mail je příliš dlouhý.')
      ->required('phone', $data['phone'], 'Telefon je povinný.')
      ->pattern('phone', $data['phone'], '/^[\d\s\+\-\(\)]{9,20}$/', 'Neplatný formát telefonu.')
      ->required('street', $data['street'], 'Ulice je povinná.')
      ->maxLength('street', $data['street'], 100, 'Ulice je příliš dlouhá.')
      ->required('city', $data['city'], 'Město je povinné.')
      ->maxLength('city', $data['city'], 60, 'Město je příliš dlouhé.')
      ->required('zip', $data['zip'], 'PSČ je povinné.')
      ->pattern('zip', $data['zip'], '/^\d{3}\s?\d{2}$/', 'PSČ musí mít 5 číslic.')
      ->maxLength('note', $data['note'], 500, 'Poznámka je příliš dlouhá.');

    if ($v->isValid()) {
        $_SESSION['order']['address'] = $data;
        redirect('/objednavka-2.php');
    }
    $errors = $v->getErrors();
}

$pageTitle = 'Objednávka – krok 1 — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="checkout-section">
    <div class="container">
        <h1 class="checkout-title">Dokončení objednávky</h1>

        <div class="checkout-steps">
            <div class="step active"><div class="step-number">1</div><div class="step-label">Dodací adresa</div></div>
            <div class="step-line"></div>
            <div class="step"><div class="step-number">2</div><div class="step-label">Doprava a platba</div></div>
            <div class="step-line"></div>
            <div class="step"><div class="step-number">3</div><div class="step-label">Shrnutí</div></div>
        </div>

        <div class="checkout-container">
            <div class="checkout-form">
                <h2>Dodací adresa</h2>
                <form method="post" action="/objednavka-1.php">
                    <?php
                    $field = function(string $name, string $label, string $type = 'text') use ($data, $errors) {
                        $err = $errors[$name] ?? null;
                        echo '<div class="form-group" style="margin-bottom:16px;">';
                        echo '<label for="' . e($name) . '">' . e($label) . ' *</label>';
                        echo '<input type="' . e($type) . '" id="' . e($name) . '" name="' . e($name) . '" value="' . e((string)($data[$name] ?? '')) . '"';
                        if ($err !== null) echo ' class="input--error" style="border-color:#ef4444;"';
                        echo '>';
                        if ($err !== null) echo '<span class="error" style="color:#ef4444;font-size:13px;">' . e($err) . '</span>';
                        echo '</div>';
                    };
                    $field('first_name', 'Jméno');
                    $field('last_name', 'Příjmení');
                    $field('email', 'E-mail', 'email');
                    $field('phone', 'Telefon', 'tel');
                    $field('street', 'Ulice a č.p.');
                    $field('city', 'Město');
                    $field('zip', 'PSČ');
                    ?>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label for="note">Poznámka</label>
                        <textarea id="note" name="note" rows="3"><?= e($data['note']) ?></textarea>
                        <?php if (isset($errors['note'])): ?><span class="error" style="color:#ef4444;font-size:13px;"><?= e($errors['note']) ?></span><?php endif; ?>
                    </div>

                    <div style="display:flex;justify-content:space-between;margin-top:24px;">
                        <a href="/kosik.php" class="btn-secondary">← Zpět do košíku</a>
                        <button type="submit" class="btn-primary">Pokračovat →</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
