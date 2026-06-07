<?php
declare(strict_types=1);

final class ShippingMethodDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $code,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price,
        public readonly string $deliveryTime,
    ) {}

    /** @param array<string,mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (string)$row['code'],
            (string)$row['name'],
            (string)($row['description'] ?? ''),
            (float)$row['price'],
            (string)($row['delivery_time'] ?? ''),
        );
    }
}
