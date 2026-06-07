<?php
declare(strict_types=1);
require_once __DIR__ . '/src/bootstrap.php';

$pageTitle = 'O nás — Boston Bruins Fan Shop';
require __DIR__ . '/partials/header.php';
?>

<section class="page-header">
    <div class="container">
        <div class="breadcrumb"><a href="/index.php">Domů</a> / O nás</div>
        <h1>O nás</h1>
        <p>Přinášíme oficiální merchandise Boston Bruins do České republiky a na Slovensko.</p>
    </div>
</section>

<section class="content-page">
    <div class="container">
        <div class="content-layout">
            <div class="content-section">
                <h2>Vítejte ve Fan Shopu</h2>
                <p>Jsme prvním a jediným oficiálním prodejcem merchandise Boston Bruins v České republice a na Slovensku. Naše mise je jasná — přinést českým a slovenským fanouškům nejkvalitnější produkty jejich oblíbeného týmu.</p>
                <p>Boston Bruins patří mezi nejstarší a nejúspěšnější týmy NHL. Od založení v roce 1924 získali šest Stanley Cupů a vytvořili legendy jako Bobby Orr, Ray Bourque nebo Zdeno Chára. Dnes pokračují v této tradici s hvězdami jako David Pastrňák.</p>

                <h2>Proč nakupovat u nás</h2>
                <div class="feature-grid">
                    <div class="feature-box">
                        <div class="feature-icon">✓</div>
                        <h3>100% Originál</h3>
                        <p>Všechny produkty jsou oficiálně licencované a pocházejí přímo od výrobců schválených NHL.</p>
                    </div>
                    <div class="feature-box">
                        <div class="feature-icon">🚚</div>
                        <h3>Rychlé doručení</h3>
                        <p>Skladem v Praze. Odesíláme do druhého dne po celé ČR a SK.</p>
                    </div>
                    <div class="feature-box">
                        <div class="feature-icon">💬</div>
                        <h3>Zákaznická podpora</h3>
                        <p>Jsme fanoušci jako vy. Rádi vám poradíme s výběrem.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
