<?php
declare(strict_types=1);

namespace App\Entity;

class Product
{
    public ?int $id;
    public string $name;
    public string $sku;
    public string $description;
    public string $category;
    public float $price;
    public float $cost_price;
    public int $stock_quantity;
    public int $min_stock_level;
    public string $supplier_name;
    public string $supplier_contact;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(
        ?int $id,
        string $name,
        string $sku,
        string $description,
        string $category,
        float $price,
        float $cost_price,
        int $stock_quantity,
        int $min_stock_level,
        string $supplier_name,
        string $supplier_contact,
        ?string $created_at = null,
        ?string $updated_at = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->sku = $sku;
        $this->description = $description;
        $this->category = $category;
        $this->price = $price;
        $this->cost_price = $cost_price;
        $this->stock_quantity = $stock_quantity;
        $this->min_stock_level = $min_stock_level;
        $this->supplier_name = $supplier_name;
        $this->supplier_contact = $supplier_contact;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}

