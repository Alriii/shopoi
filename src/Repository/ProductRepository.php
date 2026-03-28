<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use PDO;

class ProductRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Product $product): int
    {
        $sql = "INSERT INTO products
            (name, sku, description, category, price, cost_price, stock_quantity, min_stock_level, supplier_name, supplier_contact, created_at, updated_at)
            VALUES
            (:name, :sku, :description, :category, :price, :cost_price, :stock_quantity, :min_stock_level, :supplier_name, :supplier_contact, NOW(), NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name' => $product->name,
            ':sku' => $product->sku,
            ':description' => $product->description,
            ':category' => $product->category,
            ':price' => $product->price,
            ':cost_price' => $product->cost_price,
            ':stock_quantity' => $product->stock_quantity,
            ':min_stock_level' => $product->min_stock_level,
            ':supplier_name' => $product->supplier_name,
            ':supplier_contact' => $product->supplier_contact,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findAll(?float $minPrice, ?float $maxPrice, ?int $minStock, ?string $category): array
    {
        $where = [];
        $params = [];

        if ($minPrice !== null) {
            $where[] = 'price >= :minPrice';
            $params[':minPrice'] = $minPrice;
        }
        if ($maxPrice !== null) {
            $where[] = 'price <= :maxPrice';
            $params[':maxPrice'] = $maxPrice;
        }
        if ($minStock !== null) {
            $where[] = 'stock_quantity >= :minStock';
            $params[':minStock'] = $minStock;
        }
        if ($category !== null && $category !== '') {
            $where[] = 'category = :category';
            $params[':category'] = $category;
        }

        $sql = 'SELECT * FROM products';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY id DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }

    public function update(int $id, array $fields): bool
    {
        if (empty($fields)) {
            return false;
        }

        $set = [];
        $params = [':id' => $id];
        foreach ($fields as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        $set[] = "updated_at = NOW()";

        $sql = 'UPDATE products SET ' . implode(', ', $set) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

