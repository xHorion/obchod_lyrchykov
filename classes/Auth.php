<?php
namespace App;

class Auth
{
    protected $mysqli;

    public function __construct(\mysqli $mysqli) // <-- Додано \ перед mysqli
    {
        $this->mysqli = $mysqli;
    }

    public function login(string $username, string $password): bool
    {
        $user = new User($this->mysqli);
        if (!$user->loadByUsername($username)) {
            return false; // користувача немає
        }

        if (!$user->verifyPassword($password)) {
            return false; // пароль не співпадає
        }

        // Встановлюємо сесію
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['role'] = $user->getRole();

        return true;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }
}