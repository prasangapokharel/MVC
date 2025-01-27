<?php

namespace Godsu\Mvc\Controllers;

use PDO;
use Godsu\Mvc\Utility\cache\CacheConstruct;


class ContactController
{
    private $cache;

    public function __construct()
    {
        // Initialize the cache using the CacheConstruct class
        $this->cache = CacheConstruct::createCache();
    }

    public function index()
    {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $subject = $_POST['subject'] ?? null;
            $message = $_POST['message'] ?? null;

            // Validate the input
            if ($name && $email && $subject && $message) {
                // Save to database
                $this->saveContact($name, $email, $subject, $message);

                // Cache the success message
                $this->cache->deleteItem('contact_form');
                $this->cache->get('contact_form', function() use ($name) {
                    return "Thank you, $name! Your message has been submitted.";
                });

                // Redirect or show a success message
                echo "<p class='text-green-600'>Thank you! Your message has been submitted.</p>";
                return;
            } else {
                echo "<p class='text-red-600'>Please fill in all fields.</p>";
            }
        }

        // Load the contact view
        require __DIR__ . '/../views/contact.php';
    }

    private function saveContact($name, $email, $subject, $message)
    {
        // Load the database configuration
        $config = require __DIR__ . '/../config/database.php';

        // Create a PDO instance
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['username'], $config['password']);

        // Insert the data into the contacts table
        $stmt = $pdo->prepare('INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)');
        $stmt->execute([
            ':name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            ':email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            ':subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
            ':message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        ]);
    }
}
