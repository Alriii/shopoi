# shopoi

Basic PHP product inventory (CRUD) with Controller → Service → Repository layers and MySQL.

## Requirements
- PHP 8.0+ with PDO MySQL
- MySQL 8+ (or MariaDB 10.4+)

## Database
1) Create database:

```sql
CREATE DATABASE shopoi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2) Create table:

```sql
SOURCE db/schema.sql;
```

## Configuration
Set environment variables or edit `config/db.php` defaults:
- `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`

## Run (built-in server)
```bash
php -S localhost:8000 -t .
```

## Endpoints
- POST `/products` (JSON body: all fields) — price > 0, stock > 0
- GET `/products?minPrice=&maxPrice=&minStock=&category=`
- GET `/products/{id}`
- PUT `/products/{id}` (JSON body: fields to update; no negatives)
- DELETE `/products/{id}`

## Example JSON
```json
{
  "name": "Sample",
  "sku": "SKU-001",
  "description": "Sample product",
  "category": "General",
  "price": 100.5,
  "cost_price": 80,
  "stock_quantity": 10,
  "min_stock_level": 2,
  "supplier_name": "Acme Co",
  "supplier_contact": "acme@example.com"
}
```

