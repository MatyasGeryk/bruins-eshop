<?php
declare(strict_types=1);

final class ProductParameterDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly string $name,
        public readonly string $value,
    ) {}

    /** @param array<string,mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (int)$row['product_id'],
            (string)$row['name'],
            (string)$row['value'],
        );
    }
}
