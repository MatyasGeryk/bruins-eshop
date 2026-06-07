<?php
declare(strict_types=1);

final class CustomerRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /**
     * @param array<string,string> $data
     */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO customers (first_name, last_name, email, phone, street, city, zip, note, created_at)
                VALUES (:first_name, :last_name, :email, :phone, :street, :city, :zip, :note, :created_at)';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'street'     => $data['street'],
            'city'       => $data['city'],
            'zip'        => $data['zip'],
            'note'       => $data['note'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return (int)$this->pdo->lastInsertId();
    }
}
