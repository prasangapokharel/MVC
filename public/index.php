<?php
// public/index.php

require __DIR__ . '/../app/bootstrap.php';

// Parse the URL from the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$url = trim(parse_url($requestUri, PHP_URL_PATH), '/');
$url = $url ?: 'home'; // Default to home if empty

// Route the request
switch ($url) {
    case 'about':
        $controller = new Godsu\Mvc\Controllers\AboutController();
        break;
    case 'contact':
        $controller = new Godsu\Mvc\Controllers\ContactController();
        break;
    default:
        $controller = new Godsu\Mvc\Controllers\HomeController();
        break;
}

$controller->index();