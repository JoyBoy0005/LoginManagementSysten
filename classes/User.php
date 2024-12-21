<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($firstname, $lastname, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        return $this->db->query($sql, [$firstname, $lastname, $email, $hashedPassword]);
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $user = $this->db->fetch($sql, [$email]);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            return true;
        }
        return false;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
?>
