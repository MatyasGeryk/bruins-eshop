<?php
declare(strict_types=1);

final class ProductVariantDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly string $name,
        public readonly string $value,
        public readonly ?float $priceModifier,
    ) {}

    /** @param array<string,mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (int)$row['product_id'],
            (string)$row['name'],
            (string)$row['value'],
            isset($row['price_modifier']) && $row['price_modifier'] !== null ? (float)$row['price_modifier'] : null,
        );
    }
}
