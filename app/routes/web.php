<?php

use Godsu\Mvc\Controllers\HomeController;
use Godsu\Mvc\Controllers\AboutController;
use Godsu\Mvc\Controllers\ContactController;
use Godsu\Mvc\Controllers\ServiceController;

return [
    '/' => [HomeController::class, 'index'],
    'home' => [HomeController::class, 'index'],
    'about' => [AboutController::class, 'index'],
    'contact' => [ContactController::class, 'index'],
    'service' => [ServiceController::class, 'index'],

];
