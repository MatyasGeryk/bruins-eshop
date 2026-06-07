<?php
declare(strict_types=1);

final class PaymentMethodRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /** @return PaymentMethodDTO[] */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM payment_methods ORDER BY fee ASC');
        $out = [];
        foreach ($stmt->fetchAll() as $row) {
            $out[] = PaymentMethodDTO::fromRow($row);
        }
        return $out;
    }

    public function getByCode(string $code): ?PaymentMethodDTO
    {
        $stmt = $this->pdo->prepare('SELECT * FROM payment_methods WHERE code = :c LIMIT 1');
        $stmt->execute(['c' => $code]);
        $row = $stmt->fetch();
        return $row ? PaymentMethodDTO::fromRow($row) : null;
    }
}
