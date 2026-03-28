<?php
declare(strict_types=1);

require __DIR__ . '/config/db.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\Controller\ProductController;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function jsonInput(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || $raw === '') {
        return [];
    }
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function respond($data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

try {
    $pdo = getPdoConnection();
    $repository = new ProductRepository($pdo);
    $service = new ProductService($repository);
    $controller = new ProductController($service);

    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

    // Routes
    // POST   /products
    // GET    /products
    // GET    /products/{id}
    // PUT    /products/{id}
    // DELETE /products/{id}

    if ($path === '/products' && $method === 'POST') {
        $result = $controller->create(jsonInput());
        respond($result, 201);
        exit;
    }

    if ($path === '/products' && $method === 'GET') {
        $result = $controller->getAll($_GET);
        respond($result, 200);
        exit;
    }

    if (preg_match('#^/products/([0-9]+)$#', $path, $m)) {
        $id = (int)$m[1];
        if ($method === 'GET') {
            $product = $controller->getOne($id);
            if ($product === null) {
                respond(['message' => 'Not found'], 404);
            } else {
                respond($product, 200);
            }
            exit;
        }
        if ($method === 'PUT') {
            $ok = $controller->update($id, jsonInput());
            respond(['updated' => $ok], $ok ? 200 : 400);
            exit;
        }
        if ($method === 'DELETE') {
            $ok = $controller->delete($id);
            respond(['deleted' => $ok], $ok ? 200 : 400);
            exit;
        }
    }

    respond(['message' => 'Route not found'], 404);
} catch (Throwable $e) {
    respond(['error' => $e->getMessage()], 400);
}

