<?php
declare(strict_types=1);

final class OrderRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::get();
    }

    /**
     * @param array<int,array<string,mixed>> $items detailní položky košíku
     */
    public function create(
        int $customerId,
        string $orderNumber,
        int $shippingMethodId,
        int $paymentMethodId,
        float $itemsTotal,
        float $shippingPrice,
        float $paymentFee,
        float $totalPrice,
        array $items
    ): int {
        $this->pdo->beginTransaction();
        try {
            $sql = 'INSERT INTO orders
                (order_number, customer_id, shipping_method_id, payment_method_id,
                 items_total, shipping_price, payment_fee, total_price, status, created_at)
                VALUES
                (:order_number, :customer_id, :shipping_method_id, :payment_method_id,
                 :items_total, :shipping_price, :payment_fee, :total_price, :status, :created_at)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'order_number'       => $orderNumber,
                'customer_id'        => $customerId,
                'shipping_method_id' => $shippingMethodId,
                'payment_method_id'  => $paymentMethodId,
                'items_total'        => $itemsTotal,
                'shipping_price'     => $shippingPrice,
                'payment_fee'        => $paymentFee,
                'total_price'        => $totalPrice,
                'status'             => 'new',
                'created_at'         => date('Y-m-d H:i:s'),
            ]);
            $orderId = (int)$this->pdo->lastInsertId();

            $itemStmt = $this->pdo->prepare(
                'INSERT INTO order_items
                 (order_id, product_id, product_name, variant_name, variant_value, unit_price, quantity, subtotal)
                 VALUES (:order_id, :product_id, :product_name, :variant_name, :variant_value, :unit_price, :quantity, :subtotal)'
            );

            foreach ($items as $row) {
                /** @var ProductDTO $p */
                $p = $row['product'];
                /** @var ProductVariantDTO|null $v */
                $v = $row['variant'];
                $itemStmt->execute([
                    'order_id'      => $orderId,
                    'product_id'    => $p->id,
                    'product_name'  => $p->name,
                    'variant_name'  => $v?->name,
                    'variant_value' => $v?->value,
                    'unit_price'    => $row['unit_price'],
                    'quantity'      => $row['quantity'],
                    'subtotal'      => $row['subtotal'],
                ]);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public static function generateOrderNumber(): string
    {
        return 'BB-' . date('Y') . '-' . str_pad((string)random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }
}
