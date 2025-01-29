<?php
namespace Godsu\Mvc\Models;

use PDO;
use PDOException;

class UserModel
{
    private $db;

    public function __construct()
    {
        // Load database configuration
        $config = require __DIR__ . '/../../app/config/database.php';

        // Connect to the database
        try {
            $this->db = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password']
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Fetch all users
    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a user by ID
    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new user
    public function createUser($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email) 
            VALUES (:name, :email)
        ");
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    // Update a user
    public function updateUser($id, $data)
    {
        $data['id'] = $id;
        $stmt = $this->db->prepare("
            UPDATE users 
            SET name = :name, email = :email 
            WHERE id = :id
        ");
        return $stmt->execute($data);
    }

    // Delete a user
    public function deleteUser($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}