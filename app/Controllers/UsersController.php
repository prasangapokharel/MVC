<?php
namespace Godsu\Mvc\Controllers;
use Godsu\Mvc\Models\UserModel;

class UsersController
{
    public function index():void
    {

        $userModel = new UserModel();
        $users = $userModel->getAllUsers();
        require __DIR__ ."/../views/users.php";
    }
}