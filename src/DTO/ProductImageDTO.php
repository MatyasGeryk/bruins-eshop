<?php
declare(strict_types=1);

final class ProductImageDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly string $path,
        public readonly string $alt,
    ) {}

    /** @param array<string,mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (int)$row['product_id'],
            (string)$row['path'],
            (string)($row['alt'] ?? ''),
        );
    }
}
