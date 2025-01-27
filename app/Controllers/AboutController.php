<?php
// app/controllers/AboutController.php

namespace Godsu\Mvc\Controllers;

class AboutController
{
    public function index()
    {
        require __DIR__ . '/../views/about.php';
    }
}
