# Boston Bruins Fan Shop — PHP E-shop

Funkční e-shop postavený na PHP 8.3 + SQLite. Vznikl jako 2. fáze projektu PRG (převod statického HTML/CSS frontendu na dynamický backend).

## Technologie

- **PHP 8.3** (`declare(strict_types=1)`)
- **SQLite** (PDO + prepared statements)
- **Sessions** (košík, vícekrokový checkout)
- Repository + DTO pattern
- Server-side validace (vlastní třída `Validator` s fluent interface)
- Post/Redirect/Get
- `htmlspecialchars()` na všech výpisech (prevence XSS)

## Adresářová struktura

```
/
├── index.php, kategorie.php, produkty.php, produkt.php
├── kosik.php
├── objednavka-1.php, objednavka-2.php, objednavka-3.php
├── objednavka-potvrzeni.php
├── vyhledavani.php, o-nas.php, kontakt.php, 404.php
├── router.php                # router pro PHP built-in server
├── src/
│   ├── bootstrap.php         # autoload, session, helpery
│   ├── Database.php          # PDO singleton
│   ├── Cart.php              # košík v session
│   ├── Validator.php         # fluent validátor
│   ├── DTO/                  # datové objekty
│   └── Repository/           # přístup k DB
├── partials/
│   ├── header.php, footer.php, product-card.php
├── database/
│   ├── init.php              # vytvoří DB + vzorová data
│   └── eshop.db              # SQLite (negitujeme)
└── assets/
    └── css/main.css
```

## Spuštění lokálně

Potřebuješ **PHP 8.3+** s extension `pdo_sqlite`.

```bash
# 1. Inicializuj databázi (vzorová data Boston Bruins)
php database/init.php

# 2. Spusť vestavěný server
php -S localhost:8000 router.php
```

Otevři http://localhost:8000

## Spuštění v GitHub Codespaces

```bash
php database/init.php
php -S 0.0.0.0:8000 router.php
```

Codespaces ti nabídne port forwarding na 8000.

## Funkce

- Dynamický výpis kategorií a produktů z DB
- Detail produktu s galerií, parametry a variantami (velikost / barva)
- Plně funkční košík (přidání, odebrání, změna množství, varianty)
- 3krokový checkout s validací (adresa → doprava+platba → shrnutí)
- Uložení objednávky do DB, generování čísla objednávky
- Vyhledávání podle názvu a popisu
- Vlastní 404 stránka
- Kontaktní formulář se server-side validací

## Bezpečnost

- ✅ Prepared statements pro všechny SQL dotazy
- ✅ `htmlspecialchars()` na každém výpisu (helper `e()`)
- ✅ Server-side validace všech formulářů
- ✅ Post/Redirect/Get u všech state-modifikujících formulářů
- ✅ `declare(strict_types=1)` v každém PHP souboru
- ✅ Žádné inline styly v doménové logice (CSS v externím souboru)

## Validator – příklad použití

```php
$v = new Validator();
$v->required('email', $email, 'E-mail je povinný.')
  ->email('email', $email, 'Neplatný formát e-mailu.')
  ->required('zip', $zip, 'PSČ je povinné.')
  ->pattern('zip', $zip, '/^\d{3}\s?\d{2}$/', 'PSČ musí mít 5 číslic.');

if (!$v->isValid()) {
    $errors = $v->getErrors();
}
```
