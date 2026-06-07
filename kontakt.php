<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$errors = [];
$sent = false;
$data = ['name' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name'    => trim((string)($_POST['name'] ?? '')),
        'email'   => trim((string)($_POST['email'] ?? '')),
        'message' => trim((string)($_POST['message'] ?? '')),
    ];

    $v = new Validator();
    $v->required('name', $data['name'], 'Jméno je povinné.')
      ->minLength('name', $data['name'], 2, 'Jméno musí mít alespoň 2 znaky.')
      ->maxLength('name', $data['name'], 100, 'Jméno je příliš dlouhé.')
      ->required('email', $data['email'], 'E-mail je povinný.')
      ->email('email', $data['email'], 'Neplatný formát e-mailu.')
      ->required('message', $data['message'], 'Zpráva je povinná.')
      ->minLength('message', $data['message'], 10, 'Zpráva musí mít alespoň 10 znaků.')
      ->maxLength('message', $data['message'], 2000, 'Zpráva je příliš dlouhá.');

    if ($v->isValid()) {
        $_SESSION['contact_sent'] = true;
        redirect('/kontakt.php');
    }
    $errors = $v->getErrors();
}

if (!empty($_SESSION['contact_sent'])) {
    $sent = true;
    unset($_SESSION['contact_sent']);
}

$pageTitle = 'Kontakt — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="/index.php">Domů</a> / Kontakt</div>
        <h1>Kontakt</h1>
        <p>Jsme tu pro vás. Neváhejte nás kontaktovat s jakýmkoliv dotazem.</p>
    </div>
</section>

<section class="contact-page">
    <div class="container">
        <div class="contact-layout">
            <div class="contact-info-section">
                <div class="contact-card">
                    <div class="contact-icon">📍</div>
                    <h3>Adresa prodejny</h3>
                    <p>Václavské náměstí 123, 110 00 Praha 1</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <h3>Telefon</h3>
                    <p>+420 123 456 789</p>
                </div>
                <div class="contact-card">
                    <div class="contact-icon">✉</div>
                    <h3>E-mail</h3>
                    <p>info@bostonbruins-shop.cz</p>
                </div>
            </div>

            <div class="contact-form-section">
                <h2>Napište nám</h2>
                <?php if ($sent): ?>
                    <div style="background:#10b981;color:#fff;padding:16px;border-radius:8px;margin-bottom:16px;">Zpráva byla odeslána. Děkujeme!</div>
                <?php endif; ?>

                <form method="post" action="/kontakt.php">
                    <div class="form-group" style="margin-bottom:16px;">
                        <label for="name">Jméno *</label>
                        <input type="text" id="name" name="name" value="<?= e($data['name']) ?>" <?= isset($errors['name']) ? 'class="input--error" style="border-color:#ef4444;"' : '' ?>>
                        <?php if (isset($errors['name'])): ?><span class="error" style="color:#ef4444;"><?= e($errors['name']) ?></span><?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" value="<?= e($data['email']) ?>" <?= isset($errors['email']) ? 'class="input--error" style="border-color:#ef4444;"' : '' ?>>
                        <?php if (isset($errors['email'])): ?><span class="error" style="color:#ef4444;"><?= e($errors['email']) ?></span><?php endif; ?>
                    </div>
                    <div class="form-group" style="margin-bottom:16px;">
                        <label for="message">Zpráva *</label>
                        <textarea id="message" name="message" rows="5" <?= isset($errors['message']) ? 'class="input--error" style="border-color:#ef4444;"' : '' ?>><?= e($data['message']) ?></textarea>
                        <?php if (isset($errors['message'])): ?><span class="error" style="color:#ef4444;"><?= e($errors['message']) ?></span><?php endif; ?>
                    </div>
                    <button type="submit" class="btn-primary">Odeslat zprávu</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
