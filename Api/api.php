<?php

use Godsu\Mvc\Controllers\UserController;

$router = [];

// Register API routes
$router['GET']['/api/users'] = [UserController::class, 'getAllUsers'];
$router['GET']['/api/users/{id}'] = [UserController::class, 'getUserById'];

return $router;
