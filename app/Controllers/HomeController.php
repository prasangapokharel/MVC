<?php
// app/controllers/HomeController.php

namespace Godsu\Mvc\Controllers;

use Godsu\Mvc\Utility\cache\CacheConstruct;

class HomeController
{
    public function index()
    {
        // Use CacheConstruct to handle caching for the home page
        echo CacheConstruct::cachePage('home_page_content', function () {
            // Include the actual view content only
            require __DIR__ . '/../views/home.php';
        });
    }
}
