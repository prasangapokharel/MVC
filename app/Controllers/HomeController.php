<?php
// app/controllers/HomeController.php

namespace Godsu\Mvc\Controllers; // Match the namespace in composer.json

class HomeController
{
    public function index()
    {
        require __DIR__ . '/../views/home.php';
    }
}
