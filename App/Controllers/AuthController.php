<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Models\User;
use App\Services\AuthService;

class AuthController
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister(): void
    {
        $result = $_SESSION['result'] ?? null;
        $inputFields = $_SESSION['input_fields'] ?? ['username' => '', 'email' => ''];
        unset($_SESSION['result'], $_SESSION['input_fields']);
        require __DIR__ . '/../Views/Auth/Register.php';
    }
    public function showLogin(): void
    {
        require __DIR__ . '/../Views/Auth/Login.php';
    }

    public function handleRegister(): void
    {
        $user = new User(
            Helper::sanitizeInput($_POST['email']),
            Helper::sanitizeInput($_POST['password']),
            Helper::sanitizeInput($_POST['username'])
        );
        $result = $this->authService->register($user);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $_SESSION['input_fields'] = [
                'username' => $_POST['username'],
                'email' => $_POST['email']
            ];
            $this->showRegister();
        } else {
            $this->setUserSessionData($user);
            header("Location: /school_project/students");
            exit;
        }
    }

    public function handleLogin()
    {
        $user = new User(
            Helper::sanitizeInput($_POST['email']),
            Helper::sanitizeInput($_POST['password'])
        );
        $result = $this->authService->validateUserLogin($user);
        if (!$result->success) {
            $_SESSION['result'] = $result;
            $this->showLogin();
        } else {
            $user = (object)$result->result;
            $this->setUserSessionData($user);
            if (isset($_POST['remember_me'])) {
                $this->authService->createRememberToken($user->user_id);
            }
            header("Location: /school_project/students");
            exit;
        }
    }

    private function setUserSessionData($user)
    {
        $_SESSION['user'] = [
            'user_id' => $user->user_id,
            'username' => $user->username,
            'email' => $user->email,
        ];
    }

    public function logout()
    {

        $userId = $_SESSION['user']['user_id'];
        $this->authService->deleteUserTokens($userId);

        unset($_SESSION['user']);
        $_SESSION = [];

        session_destroy();
        header("Location: /school_project/login");
        exit;
    }
}
