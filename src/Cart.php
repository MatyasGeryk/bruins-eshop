<?php
declare(strict_types=1);

/**
 * Košík uložený v session.
 * Položka: ['product_id' => int, 'variant_id' => ?int, 'quantity' => int]
 * Klíč v session: "product_id-variant_id" (variant_id může být 0).
 */
final class Cart
{
    private const SESSION_KEY = 'cart';

    /** @return array<string,array{product_id:int,variant_id:?int,quantity:int}> */
    public static function all(): array
    {
        return $_SESSION[self::SESSION_KEY] ?? [];
    }

    public static function add(int $productId, ?int $variantId, int $quantity = 1): void
    {
        if ($quantity < 1) $quantity = 1;
        $key = self::key($productId, $variantId);
        $cart = self::all();
        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity'   => $quantity,
            ];
        }
        $_SESSION[self::SESSION_KEY] = $cart;
    }

    public static function update(string $key, int $quantity): void
    {
        $cart = self::all();
        if (!isset($cart[$key])) return;
        if ($quantity < 1) {
            unset($cart[$key]);
        } else {
            $cart[$key]['quantity'] = $quantity;
        }
        $_SESSION[self::SESSION_KEY] = $cart;
    }

    public static function remove(string $key): void
    {
        $cart = self::all();
        unset($cart[$key]);
        $_SESSION[self::SESSION_KEY] = $cart;
    }

    public static function clear(): void
    {
        $_SESSION[self::SESSION_KEY] = [];
    }

    public static function count(): int
    {
        $sum = 0;
        foreach (self::all() as $item) {
            $sum += $item['quantity'];
        }
        return $sum;
    }

    public static function isEmpty(): bool
    {
        return self::all() === [];
    }

    public static function key(int $productId, ?int $variantId): string
    {
        return $productId . '-' . ($variantId ?? 0);
    }

    /**
     * Vrátí detailní položky košíku včetně produktu, varianty a mezisoučtu.
     * @return array<int,array<string,mixed>>
     */
    public static function detailed(): array
    {
        $repo = new ProductRepository();
        $out = [];
        foreach (self::all() as $key => $item) {
            $product = $repo->getById($item['product_id']);
            if ($product === null) continue;
            $variant = null;
            if ($item['variant_id'] !== null && $item['variant_id'] > 0) {
                $variant = $repo->getVariantById($item['variant_id']);
            }
            $unit = $product->getEffectivePrice();
            $out[] = [
                'key'        => $key,
                'product'    => $product,
                'variant'    => $variant,
                'quantity'   => $item['quantity'],
                'unit_price' => $unit,
                'subtotal'   => $unit * $item['quantity'],
            ];
        }
        return $out;
    }

    public static function itemsTotal(): float
    {
        $sum = 0.0;
        foreach (self::detailed() as $row) {
            $sum += (float)$row['subtotal'];
        }
        return $sum;
    }
}
