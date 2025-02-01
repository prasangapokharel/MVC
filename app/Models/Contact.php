<?php

namespace Godsu\Mvc\Models;

use PDO;
use PDOException;

class Contact
{
    private $db;

    public function __construct()
    {
        $config = require __DIR__ . '/../Config/database.php';

        try {
            $this->db = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['username'],
                $config['password']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    
    
    public function saveContact($name, $email, $subject, $message)
    {
        $stmt = $this->db->prepare('INSERT INTO contacts (name, email, subject, message) VALUES (:name, :email, :subject, :message)');
        $stmt->execute([
            ':name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            ':email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
            ':subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
            ':message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
        ]);
    }
}

