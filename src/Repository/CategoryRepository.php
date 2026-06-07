<?php
declare(strict_types=1);

final class CategoryRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /** @return CategoryDTO[] */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM categories ORDER BY name ASC');
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[] = CategoryDTO::fromRow($row);
        }
        return $out;
    }

    public function getBySlug(string $slug): ?CategoryDTO
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ? CategoryDTO::fromRow($row) : null;
    }

    public function countProducts(int $categoryId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = :id');
        $stmt->execute(['id' => $categoryId]);
        return (int)$stmt->fetchColumn();
    }
}
