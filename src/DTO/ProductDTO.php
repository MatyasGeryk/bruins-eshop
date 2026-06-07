<?php
declare(strict_types=1);

final class ProductDTO
{
    /** @var ProductImageDTO[] */
    public array $images = [];
    /** @var ProductVariantDTO[] */
    public array $variants = [];
    /** @var ProductParameterDTO[] */
    public array $parameters = [];

    public function __construct(
        public readonly int $id,
        public readonly int $categoryId,
        public readonly string $categorySlug,
        public readonly string $categoryName,
        public readonly string $slug,
        public readonly string $name,
        public readonly string $shortDescription,
        public readonly string $description,
        public readonly float $price,
        public readonly ?float $salePrice,
        public readonly ?string $badge,
        public readonly bool $featured,
    ) {}

    public function getEffectivePrice(): float
    {
        return $this->salePrice !== null && $this->salePrice > 0
            ? $this->salePrice
            : $this->price;
    }

    public function hasVariants(): bool
    {
        return $this->variants !== [];
    }

    public function getMainImage(): ?string
    {
        return $this->images[0]->path ?? null;
    }

    /** @param array<string,mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            (int)$row['category_id'],
            (string)($row['category_slug'] ?? ''),
            (string)($row['category_name'] ?? ''),
            (string)$row['slug'],
            (string)$row['name'],
            (string)($row['short_description'] ?? ''),
            (string)($row['description'] ?? ''),
            (float)$row['price'],
            isset($row['sale_price']) && $row['sale_price'] !== null ? (float)$row['sale_price'] : null,
            $row['badge'] !== null ? (string)$row['badge'] : null,
            (bool)($row['featured'] ?? 0),
        );
    }
}
