<?php

namespace App\Services;

use App\Models\Login;
use App\Models\Result;
use App\Models\User;
use App\Repositories\UserRepository;
use DateTime;

class AuthService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(User $user): Result
    {
        $errors = $this->validateRegisterForm($user);
        if (count($errors) > 0) {
            return new Result(false, '', $errors);
        }

        $user->password = password_hash($user->password, PASSWORD_BCRYPT);

        $saved = $this->userRepository->save($user);
        if ($saved) {
            return new Result(true, 'Registration successful');
        }
        return new Result(false, 'Failed to register');
    }
    private function validateRegisterForm(User $user)
    {
        $errors = [];
        if (empty($user->username)) {
            array_push($errors, "Username is required");
        }

        if (empty($user->email)) {
            array_push($errors, "Email is required");
        } elseif (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email not valid");
        } elseif ($this->userRepository->findByEmail($user->email)) {
            array_push($errors, "Email already exits");
        }

        if (empty($user->password)) {
            array_push($errors, "Password is required");
        }

        return $errors;
    }

    public function validateUserLogin(User $userData)
    {
        $user = $this->userRepository->findByEmail($userData->email);
        if (!password_verify($userData->password, $user["password_hash"])) {
            return new Result(false, 'Invalid email or password');
        }
        $userData->user_id = $user['user_id'];
        return new Result(true, 'Invalid email or password', [], $user);

        return $user;
    }

    public function createRememberToken(int $userId)
    {
        $selector = bin2hex(random_bytes(9));
        $validator = bin2hex(random_bytes(32));

        $hashedValidator = hash('sha256', $validator);
        $expiry = new DateTime('+30 days');

        $this->userRepository->saveUserToken($selector, $hashedValidator, $userId, $expiry);

        $cookieValue = $selector . ':' . $validator;
        setcookie(
            'remember_me',
            $cookieValue,
            [
                'expires' => $expiry->getTimestamp(),
            ]
        );
    }

    public function validateRememberToken(string $selector, string $validator)
    {
        $tokenData = $this->userRepository->getUserToken($selector);

        if (!$tokenData) return null;

        if (new DateTime() > new DateTime($tokenData['expiry_date'])) {
            $this->userRepository->deleteToken($selector);
            return null;
        }

        $hashedValidator = hash('sha256', $validator);
        if (!hash_equals($tokenData['hashed_validator'], $hashedValidator)) {
            $this->userRepository->deleteToken($selector);
            return null;
        }

        $user = $this->userRepository->getUserById($tokenData['user_id']);


        return $user;
    }

    public function deleteUserTokens(int $userId): void
    {
        $this->userRepository->deleteUserTokens($userId);
    }
}
