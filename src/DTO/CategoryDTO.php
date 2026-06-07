<?php
declare(strict_types=1);

final class CategoryDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $name,
        public readonly string $description,
        public readonly ?string $image,
    ) {}

    /** @param array<string,mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (string)$row['slug'],
            (string)$row['name'],
            (string)($row['description'] ?? ''),
            $row['image'] !== null ? (string)$row['image'] : null,
        );
    }
}
