<?php
declare(strict_types=1);
/** @var string|null $pageTitle */
/** @var string|null $pageDescription */
$cartCount = Cart::count();
$searchQuery = isset($_GET['q']) ? (string)$_GET['q'] : '';
?><!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Boston Bruins Fan Shop') ?></title>
    <meta name="description" content="<?= e($pageDescription ?? 'Oficiální fan shop Boston Bruins.') ?>">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<header>
    <div class="container">
        <a href="/index.php" class="logo" style="text-decoration:none;">
            <h1>BOSTON BRUINS</h1>
            <p>Official Fan Shop</p>
        </a>
        <nav>
            <ul>
                <li><a href="/index.php">Domů</a></li>
                <li><a href="/kategorie.php">Kategorie</a></li>
                <li><a href="/o-nas.php">O nás</a></li>
                <li><a href="/kontakt.php">Kontakt</a></li>
                <li>
                    <form action="/vyhledavani.php" method="get" style="display:inline-flex;gap:6px;">
                        <input type="search" name="q" placeholder="Hledat..." value="<?= e($searchQuery) ?>"
                               style="padding:6px 10px;border-radius:4px;border:1px solid #333;background:#1c1c1c;color:#fff;">
                    </form>
                </li>
                <li><a href="/kosik.php" class="cart-link">Košík (<?= (int)$cartCount ?>)</a></li>
            </ul>
        </nav>
    </div>
</header>
<main>
