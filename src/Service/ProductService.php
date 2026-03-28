<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data): int
    {
        $this->assertRequired($data, ['name','sku','description','category','price','cost_price','stock_quantity','min_stock_level','supplier_name','supplier_contact']);
        $this->assertPositiveFloat($data['price'], 'price');
        $this->assertPositiveFloat($data['cost_price'], 'cost_price');
        $this->assertPositiveInt($data['stock_quantity'], 'stock_quantity');
        $this->assertNonNegativeInt($data['min_stock_level'], 'min_stock_level');

        $product = new Product(
            null,
            (string)$data['name'],
            (string)$data['sku'],
            (string)$data['description'],
            (string)$data['category'],
            (float)$data['price'],
            (float)$data['cost_price'],
            (int)$data['stock_quantity'],
            (int)$data['min_stock_level'],
            (string)$data['supplier_name'],
            (string)$data['supplier_contact']
        );

        return $this->repository->create($product);
    }

    public function getAll(?float $minPrice, ?float $maxPrice, ?int $minStock, ?string $category): array
    {
        return $this->repository->findAll($minPrice, $maxPrice, $minStock, $category);
    }

    public function getOne(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function update(int $id, array $fields): bool
    {
        if (isset($fields['price'])) {
            $this->assertNonNegativeFloat($fields['price'], 'price');
        }
        if (isset($fields['cost_price'])) {
            $this->assertNonNegativeFloat($fields['cost_price'], 'cost_price');
        }
        if (isset($fields['stock_quantity'])) {
            $this->assertNonNegativeInt($fields['stock_quantity'], 'stock_quantity');
        }
        if (isset($fields['min_stock_level'])) {
            $this->assertNonNegativeInt($fields['min_stock_level'], 'min_stock_level');
        }

        return $this->repository->update($id, $fields);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    private function assertRequired(array $data, array $keys): void
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException("Missing required field: {$key}");
            }
        }
    }

    private function assertPositiveFloat($value, string $name): void
    {
        if (!is_numeric($value) || (float)$value <= 0) {
            throw new \InvalidArgumentException("{$name} must be a number greater than 0");
        }
    }

    private function assertNonNegativeFloat($value, string $name): void
    {
        if (!is_numeric($value) || (float)$value < 0) {
            throw new \InvalidArgumentException("{$name} must be a number greater than or equal to 0");
        }
    }

    private function assertPositiveInt($value, string $name): void
    {
        if (!is_numeric($value) || (int)$value <= 0) {
            throw new \InvalidArgumentException("{$name} must be an integer greater than 0");
        }
    }

    private function assertNonNegativeInt($value, string $name): void
    {
        if (!is_numeric($value) || (int)$value < 0) {
            throw new \InvalidArgumentException("{$name} must be an integer greater than or equal to 0");
        }
    }
}

