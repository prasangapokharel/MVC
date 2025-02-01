<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/bootstrap.php';

header("Cache-Control: public, max-age=31536000, immutable");

$webRoutes = require __DIR__ . '/../app/routes/web.php';

$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$requestUri = $requestUri ?: '/';

function handleWebRequest($requestUri, $webRoutes)
{
    if (isset($webRoutes[$requestUri])) {
        [$controllerClass, $method] = $webRoutes[$requestUri];
        $controller = new $controllerClass();
        if (method_exists($controller, $method)) {
            $controller->$method();
            exit;
        }
    }

    http_response_code(404);
    echo "404 - Page Not Found";
}

handleWebRequest($requestUri, $webRoutes);
