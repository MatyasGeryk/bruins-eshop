<?php
declare(strict_types=1);

final class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /** @return ProductDTO[] */
    public function getFeatured(int $limit = 4): array
    {
        $sql = 'SELECT p.*, c.slug AS category_slug, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.featured = 1
                ORDER BY p.id ASC
                LIMIT :lim';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $this->hydrateList($stmt->fetchAll());
    }

    /** @return ProductDTO[] */
    public function getByCategorySlug(string $slug): array
    {
        $sql = 'SELECT p.*, c.slug AS category_slug, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE c.slug = :slug
                ORDER BY p.name ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        return $this->hydrateList($stmt->fetchAll());
    }

    public function getBySlug(string $slug): ?ProductDTO
    {
        $sql = 'SELECT p.*, c.slug AS category_slug, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.slug = :slug LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        if (!$row) return null;
        $product = ProductDTO::fromRow($row);
        $this->loadRelations($product);
        return $product;
    }

    public function getById(int $id): ?ProductDTO
    {
        $sql = 'SELECT p.*, c.slug AS category_slug, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.id = :id LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;
        $product = ProductDTO::fromRow($row);
        $this->loadRelations($product);
        return $product;
    }

    public function getVariantById(int $id): ?ProductVariantDTO
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product_variants WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? ProductVariantDTO::fromRow($row) : null;
    }

    /** @return ProductDTO[] */
    public function search(string $query): array
    {
        $sql = 'SELECT p.*, c.slug AS category_slug, c.name AS category_name
                FROM products p
                JOIN categories c ON c.id = p.category_id
                WHERE p.name LIKE :q OR p.description LIKE :q OR p.short_description LIKE :q
                ORDER BY p.name ASC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['q' => '%' . $query . '%']);
        return $this->hydrateList($stmt->fetchAll());
    }

    /** @param array<int,array<string,mixed>> $rows @return ProductDTO[] */
    private function hydrateList(array $rows): array
    {
        $list = [];
        foreach ($rows as $row) {
            $p = ProductDTO::fromRow($row);
            // pro výpisy načteme jen hlavní obrázek
            $imgStmt = $this->pdo->prepare('SELECT * FROM product_images WHERE product_id = :id ORDER BY id ASC LIMIT 1');
            $imgStmt->execute(['id' => $p->id]);
            foreach ($imgStmt->fetchAll() as $r) {
                $p->images[] = ProductImageDTO::fromRow($r);
            }
            $list[] = $p;
        }
        return $list;
    }

    private function loadRelations(ProductDTO $product): void
    {
        $stmt = $this->pdo->prepare('SELECT * FROM product_images WHERE product_id = :id ORDER BY id ASC');
        $stmt->execute(['id' => $product->id]);
        foreach ($stmt->fetchAll() as $row) {
            $product->images[] = ProductImageDTO::fromRow($row);
        }

        $stmt = $this->pdo->prepare('SELECT * FROM product_variants WHERE product_id = :id ORDER BY id ASC');
        $stmt->execute(['id' => $product->id]);
        foreach ($stmt->fetchAll() as $row) {
            $product->variants[] = ProductVariantDTO::fromRow($row);
        }

        $stmt = $this->pdo->prepare('SELECT * FROM product_parameters WHERE product_id = :id ORDER BY id ASC');
        $stmt->execute(['id' => $product->id]);
        foreach ($stmt->fetchAll() as $row) {
            $product->parameters[] = ProductParameterDTO::fromRow($row);
        }
    }
}
