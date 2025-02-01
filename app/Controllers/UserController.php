<?php

namespace Godsu\Mvc\Controllers;

use Godsu\Mvc\Models\UserModel;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getAllUsers(): void
    {
        $users = $this->userModel->getAllUsers();
        header('Content-Type: application/json');
        echo json_encode(['data' => $users]);
        exit; // Stop further processing
    }

    public function getUserById($id): void
    {
        $user = $this->userModel->getUserById($id);
        header('Content-Type: application/json');
        if ($user) {
            echo json_encode(['data' => $user]);
        } else {
            echo json_encode(['error' => 'User not found'], JSON_PRETTY_PRINT);
        }
        exit;
    }
}
