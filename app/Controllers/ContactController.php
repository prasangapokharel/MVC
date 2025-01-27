<?php
// app/controllers/ContactController.php

namespace Godsu\Mvc\Controllers;

class ContactController
{
    public function index()
    {
        require __DIR__ . '/../views/contact.php';
    }
}