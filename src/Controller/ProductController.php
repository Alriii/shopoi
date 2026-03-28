<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ProductService;

class ProductController
{
    private ProductService $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function create(array $input): array
    {
        $id = $this->service->create($input);
        return ['id' => $id];
    }

    public function getAll(array $query): array
    {
        $minPrice = isset($query['minPrice']) ? (float)$query['minPrice'] : null;
        $maxPrice = isset($query['maxPrice']) ? (float)$query['maxPrice'] : null;
        $minStock = isset($query['minStock']) ? (int)$query['minStock'] : null;
        $category = isset($query['category']) ? (string)$query['category'] : null;
        return $this->service->getAll($minPrice, $maxPrice, $minStock, $category);
    }

    public function getOne(int $id): ?array
    {
        return $this->service->getOne($id);
    }

    public function update(int $id, array $fields): bool
    {
        return $this->service->update($id, $fields);
    }

    public function delete(int $id): bool
    {
        return $this->service->delete($id);
    }
}

