<?php
// public/index.php

// Bootstrap the application
require __DIR__ . '/../app/bootstrap.php';

// Get the requested URL (e.g., /about)
$url = $_GET['url'] ?? 'home';

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

// Call the controller's index method
$controller->index();

