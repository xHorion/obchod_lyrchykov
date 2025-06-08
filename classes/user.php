<?php
namespace App;

use mysqli;

class User
{
    protected $mysqli;
    protected $id;
    protected $username;
    protected $passwordHash;
    protected $role;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Завантаження користувача за ім'ям користувача
    public function loadByUsername(string $username): bool
    {
        $stmt = $this->mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $username, $passwordHash, $role);
        if ($stmt->fetch()) {
            $this->id = $id;
            $this->username = $username;
            $this->passwordHash = $passwordHash;
            $this->role = $role;
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}