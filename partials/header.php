<?php

/**
 * PARTIAL: Hlavička stránky
 *
 * Očekává proměnnou:
 *   $pageTitle (string) – titulek stránky
 *
 * Volitelně:
 *   $cartItemCount (int) – počet položek v košíku (výchozí 0)
 */

$pageTitle ??= 'SportShop';
$cartItemCount ??= 0;

?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<header class="header">
    <a href="index.php" class="header__logo">SportShop</a>

    <nav class="header__nav">
        <a href="index.php">Domů</a>
        <a href="kategorie.php">Kategorie</a>
        <a href="o-nas.php">O nás</a>
        <a href="kontakt.php">Kontakt</a>
    </nav>

    <a href="kosik-krok1.php" class="header__cart" title="Košík">
        &#128722;
        <?php if ($cartItemCount > 0): ?>
            <span class="header__cart-badge"><?= $cartItemCount ?></span>
        <?php endif; ?>
    </a>
</header>
