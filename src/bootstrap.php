<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Cart.php';
require_once __DIR__ . '/Validator.php';

require_once __DIR__ . '/DTO/CategoryDTO.php';
require_once __DIR__ . '/DTO/ProductDTO.php';
require_once __DIR__ . '/DTO/ProductImageDTO.php';
require_once __DIR__ . '/DTO/ProductVariantDTO.php';
require_once __DIR__ . '/DTO/ProductParameterDTO.php';
require_once __DIR__ . '/DTO/ShippingMethodDTO.php';
require_once __DIR__ . '/DTO/PaymentMethodDTO.php';

require_once __DIR__ . '/Repository/CategoryRepository.php';
require_once __DIR__ . '/Repository/ProductRepository.php';
require_once __DIR__ . '/Repository/ShippingMethodRepository.php';
require_once __DIR__ . '/Repository/PaymentMethodRepository.php';
require_once __DIR__ . '/Repository/CustomerRepository.php';
require_once __DIR__ . '/Repository/OrderRepository.php';

/** Pomocná funkce pro bezpečný výpis. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Formátování ceny v Kč. */
function price(float $value): string
{
    return number_format($value, 0, ',', ' ') . ' Kč';
}

/** Přesměrování + ukončení. */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}
