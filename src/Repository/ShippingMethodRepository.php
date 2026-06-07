<?php
declare(strict_types=1);

final class ShippingMethodRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /** @return ShippingMethodDTO[] */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM shipping_methods ORDER BY price ASC');
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[] = ShippingMethodDTO::fromRow($row);
        }
        return $out;
    }

    public function getByCode(string $code): ?ShippingMethodDTO
    {
        $stmt = $this->pdo->prepare('SELECT * FROM shipping_methods WHERE code = :c LIMIT 1');
        $stmt->execute(['c' => $code]);
        $row = $stmt->fetch();
        return $row ? ShippingMethodDTO::fromRow($row) : null;
    }
}
