<?php
declare(strict_types=1);

/**
 * Inicializace SQLite databáze + vzorová data.
 * Spuštění: php database/init.php
 */

$dbPath = __DIR__ . '/eshop.db';
if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "Starou databázi smazáno.\n";
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON');

$pdo->exec(<<<SQL
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    description TEXT NOT NULL DEFAULT '',
    image TEXT
);

CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    category_id INTEGER NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    short_description TEXT NOT NULL DEFAULT '',
    description TEXT NOT NULL DEFAULT '',
    price REAL NOT NULL,
    sale_price REAL,
    badge TEXT,
    featured INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE product_images (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    path TEXT NOT NULL,
    alt TEXT NOT NULL DEFAULT '',
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE product_variants (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    value TEXT NOT NULL,
    price_modifier REAL DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE product_parameters (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    product_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    value TEXT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE shipping_methods (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    description TEXT NOT NULL DEFAULT '',
    price REAL NOT NULL,
    delivery_time TEXT NOT NULL DEFAULT ''
);

CREATE TABLE payment_methods (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    name TEXT NOT NULL,
    description TEXT NOT NULL DEFAULT '',
    fee REAL NOT NULL DEFAULT 0
);

CREATE TABLE customers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    street TEXT NOT NULL,
    city TEXT NOT NULL,
    zip TEXT NOT NULL,
    note TEXT NOT NULL DEFAULT '',
    created_at TEXT NOT NULL
);

CREATE TABLE orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_number TEXT NOT NULL UNIQUE,
    customer_id INTEGER NOT NULL,
    shipping_method_id INTEGER NOT NULL,
    payment_method_id INTEGER NOT NULL,
    items_total REAL NOT NULL,
    shipping_price REAL NOT NULL,
    payment_fee REAL NOT NULL,
    total_price REAL NOT NULL,
    status TEXT NOT NULL DEFAULT 'new',
    created_at TEXT NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (shipping_method_id) REFERENCES shipping_methods(id),
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id)
);

CREATE TABLE order_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL,
    product_id INTEGER NOT NULL,
    product_name TEXT NOT NULL,
    variant_name TEXT,
    variant_value TEXT,
    unit_price REAL NOT NULL,
    quantity INTEGER NOT NULL,
    subtotal REAL NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
SQL);

echo "Schéma vytvořeno.\n";

/* ===== KATEGORIE ===== */
$categories = [
    ['dresy-tricka', 'Dresy a trička', 'Oficiální dresy a trika s čísly hráčů Boston Bruins.', '/assets/images/cat-dresy.jpg'],
    ['hokejove-vybaveni', 'Hokejové vybavení', 'Hokejky, puky a tréninkové vybavení.', '/assets/images/cat-vybaveni.jpg'],
    ['cepice-saly', 'Čepice a šály', 'Zimní doplňky pro pravé fanoušky.', '/assets/images/cat-cepice.jpg'],
    ['mikiny', 'Mikiny a bundy', 'Pohodlné mikiny s logem Bruins.', '/assets/images/cat-mikiny.jpg'],
];
$stmt = $pdo->prepare('INSERT INTO categories (slug, name, description, image) VALUES (?,?,?,?)');
foreach ($categories as $c) $stmt->execute($c);

/* ===== PRODUKTY ===== */
$products = [
    // category_slug, slug, name, short, desc, price, sale, badge, featured, [images], [variants], [params]
    ['dresy-tricka', 'home-jersey-pastrnak-88',
     'Home Jersey 2025 — Pastrňák #88',
     'Oficiální domácí dres s číslem 88',
     'Oficiální domácí dres Boston Bruins s číslem hvězdného útočníka Davida Pastrňáka. Vyrobeno z prémiových materiálů s profesionální kvalitou tisku čísel a jmen.',
     2499.0, null, 'BESTSELLER', 1,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [['Velikost','S',0],['Velikost','M',0],['Velikost','L',0],['Velikost','XL',0]],
     [['Materiál','100% polyester'],['Výrobce','Fanatics'],['Země původu','USA']]],

    ['dresy-tricka', 'away-jersey-mcavoy-73',
     'Away Jersey 2025 — McAvoy #73',
     'Bílý venkovní dres obránce',
     'Venkovní bílý dres Charlieho McAvoye, klíčového obránce Boston Bruins.',
     2399.0, 1999.0, 'AKCE', 1,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [['Velikost','M',0],['Velikost','L',0],['Velikost','XL',0]],
     [['Materiál','100% polyester'],['Výrobce','Fanatics']]],

    ['dresy-tricka', 'tricko-bruins-logo',
     'Tričko Bruins Classic Logo',
     'Bavlněné tričko s klasickým logem',
     'Pohodlné bavlněné tričko s klasickým logem Boston Bruins. Ideální na zápas i do města.',
     599.0, null, null, 1,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [['Velikost','S',0],['Velikost','M',0],['Velikost','L',0],['Velikost','XL',0],['Velikost','XXL',100]],
     [['Materiál','100% bavlna'],['Praní','30°C']]],

    ['cepice-saly', 'ksiltovka-heritage',
     'Kšiltovka Heritage Adjustable',
     'Klasická černo-zlatá kšiltovka',
     'Stylová kšiltovka v ikonických barvách Boston Bruins. Univerzální velikost s nastavitelným páskem.',
     749.0, null, 'NOVINKA', 1,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [],
     [['Materiál','Bavlna / polyester'],['Velikost','Univerzální']]],

    ['cepice-saly', 'zimni-cepice-pom',
     'Zimní čepice s bambulí',
     'Teplá pletená čepice',
     'Teplá zimní čepice s bambulí a vyšitým logem Bruins.',
     449.0, 349.0, 'AKCE', 0,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [['Barva','Černá',0],['Barva','Zlatá',0]],
     [['Materiál','100% akryl']]],

    ['hokejove-vybaveni', 'mini-stick-bruins',
     'Mini hokejka Boston Bruins',
     'Sběratelská mini hokejka',
     'Mini hokejka s logem Boston Bruins. Ideální pro sběratele a fanoušky.',
     299.0, null, null, 0,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [],
     [['Délka','35 cm'],['Materiál','Dřevo']]],

    ['hokejove-vybaveni', 'puk-oficialni',
     'Oficiální puk NHL Bruins',
     'Originální NHL puk s logem',
     'Originální zápasový puk NHL s logem Boston Bruins.',
     249.0, null, null, 0,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [],
     [['Hmotnost','170 g'],['Materiál','Vulkanizovaná guma']]],

    ['mikiny', 'mikina-hoodie-logo',
     'Mikina s kapucí — Bruins Logo',
     'Pohodlná hoodie mikina',
     'Mikina s kapucí v černé barvě s velkým vyšitým logem Boston Bruins.',
     1499.0, null, 'BESTSELLER', 1,
     ['https://cdn.myshoptet.com/usr/www.fanda-nhl.cz/user/shop/related/127214-4_pansky-dres-david-pastrnak--88-boston-bruins-nhl-premium-home-jersey.jpg?68e50fd7'],
     [['Velikost','S',0],['Velikost','M',0],['Velikost','L',0],['Velikost','XL',0]],
     [['Materiál','80% bavlna, 20% polyester'],['Praní','30°C']]],
];

$prodStmt = $pdo->prepare(
    'INSERT INTO products (category_id, slug, name, short_description, description, price, sale_price, badge, featured)
     VALUES (?,?,?,?,?,?,?,?,?)'
);
$imgStmt = $pdo->prepare('INSERT INTO product_images (product_id, path, alt) VALUES (?,?,?)');
$varStmt = $pdo->prepare('INSERT INTO product_variants (product_id, name, value, price_modifier) VALUES (?,?,?,?)');
$parStmt = $pdo->prepare('INSERT INTO product_parameters (product_id, name, value) VALUES (?,?,?)');
$catStmt = $pdo->prepare('SELECT id FROM categories WHERE slug = ?');

foreach ($products as $p) {
    [$catSlug, $slug, $name, $short, $desc, $price, $sale, $badge, $featured, $images, $variants, $params] = $p;
    $catStmt->execute([$catSlug]);
    $catId = (int)$catStmt->fetchColumn();
    $prodStmt->execute([$catId, $slug, $name, $short, $desc, $price, $sale, $badge, $featured]);
    $pid = (int)$pdo->lastInsertId();
    foreach ($images as $img) $imgStmt->execute([$pid, $img, $name]);
    foreach ($variants as $v) $varStmt->execute([$pid, $v[0], $v[1], $v[2]]);
    foreach ($params as $pa) $parStmt->execute([$pid, $pa[0], $pa[1]]);
}

/* ===== DOPRAVA ===== */
$shipping = [
    ['zasilkovna', 'Zásilkovna', 'Doručení na pobočku', 79.0, '1–3 prac. dny'],
    ['ppl', 'PPL', 'Doručení na adresu', 119.0, '1–2 prac. dny'],
    ['osobni-odber', 'Osobní odběr Praha', 'Václavské náměstí 123', 0.0, 'Ihned po potvrzení'],
];
$stmt = $pdo->prepare('INSERT INTO shipping_methods (code, name, description, price, delivery_time) VALUES (?,?,?,?,?)');
foreach ($shipping as $s) $stmt->execute($s);

/* ===== PLATBA ===== */
$payments = [
    ['prevod', 'Bankovní převod', 'Platba předem na účet', 0.0],
    ['karta', 'Platební karta online', 'Bezpečná platba kartou', 0.0],
    ['dobirka', 'Dobírka', 'Zaplatíte při převzetí', 49.0],
];
$stmt = $pdo->prepare('INSERT INTO payment_methods (code, name, description, fee) VALUES (?,?,?,?)');
foreach ($payments as $p) $stmt->execute($p);

echo "Vzorová data nahrána.\n";
echo "Hotovo. Databáze: $dbPath\n";
