<?php


use Godsu\Mvc\Controllers\HomeController;
use Godsu\Mvc\Controllers\AboutController;
use Godsu\Mvc\Controllers\ContactController;
use Godsu\Mvc\Controllers\ServiceController;
use Godsu\Mvc\Controllers\DeveloperController;
use Godsu\Mvc\Controllers\UserController;

return [
    '/' => [HomeController::class, 'index'],
    'users' => [UserController::class, 'index'],
    'service' => [ServiceController::class, 'index'],
    'users/create' => [UserController::class, 'create'],
    'users/store' => [UserController::class, 'store'],
    'users/{id}' => [UserController::class, 'show'],
    'users/{id}/edit' => [UserController::class, 'edit'],
    'users/{id}/update' => [UserController::class, 'update'],
    'users/{id}/delete' => [UserController::class, 'delete'],



];



