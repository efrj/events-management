<?php

require_once __DIR__ . '/../autoload.php';

(new \App\Config\DotEnv(__DIR__ . '/../.env'))->load();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controller = new \App\Controllers\EventController();

$uri = rtrim($uri, '/');

$parts = explode('/', $uri);
$resource = $parts[1] ?? '';
$id = $parts[2] ?? null;

if ($resource !== 'events') {
    \App\Services\ApiResponse::error('Recurso não encontrado', 404);
}

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if ($id) {
                $controller->show((int)$id);
            } else {
                $controller->index();
            }
            break;

        case 'POST':
            if (!$id) {
                $controller->create();
            } else {
                \App\Services\ApiResponse::error('Método não permitido', 405);
            }
            break;

        case 'PUT':
            if ($id) {
                $controller->update((int)$id);
            } else {
                \App\Services\ApiResponse::error('ID do evento não fornecido', 400);
            }
            break;

        case 'DELETE':
            if ($id) {
                $controller->delete((int)$id);
            } else {
                \App\Services\ApiResponse::error('ID do evento não fornecido', 400);
            }
            break;

        default:
            \App\Services\ApiResponse::error('Método não permitido', 405);
    }
} catch (\Exception $e) {
    \App\Services\ApiResponse::error('Erro interno do servidor', 500);
}
