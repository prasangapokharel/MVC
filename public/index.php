<?php
// public/index.php
require __DIR__ . '/../app/bootstrap.php';
header("Cache-Control: public, max-age=31536000, immutable"); // Cache for 1 year

// Load routes
$routes = require __DIR__ . '/../app/routes/web.php';

// Parse the URL from the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$url = trim(parse_url($requestUri, PHP_URL_PATH), '/');
$url = $url ?: '/'; // Default to home if empty

// Match the route
if (isset($routes[$url])) {
    [$controllerClass, $method] = $routes[$url];
    $controller = new $controllerClass();
    if (method_exists($controller, $method)) {
        $controller->$method();
        exit;
    }
}

// Fallback for unmatched routes
http_response_code(404);
echo '404 Not Found';



