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

    // Show all users (READ)
    public function index()
    {
        $users = $this->userModel->getAllUsers();
        require __DIR__ . '/../views/users/index.php';
    }

    // Show the form to create a user
    public function create()
    {
        require __DIR__ . '/../views/users/create.php';
    }

    // Save a new user (CREATE)
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $this->userModel->createUser($data);
            header('Location: /users');
            exit;
        }
    }

    // Show a single user (READ)
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        require __DIR__ . '/../views/users/show.php';
    }

    // Show the edit form for a user
    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        require __DIR__ . '/../views/users/edit.php';
    }

    // Update a user (UPDATE)
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $this->userModel->updateUser($id, $data);
            header('Location: /users/' . $id);
            exit;
        }
    }

    // Delete a user (DELETE)
    public function delete($id)
    {
        $this->userModel->deleteUser($id);
        header('Location: /users');
        exit;
    }
}