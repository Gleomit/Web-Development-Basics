<?php

namespace Framework\Models;

use Framework\Config\Config;
use Framework\Library\Database;
use Framework\Library\BaseModel;

class UserModel extends BaseModel
{
    public function __construct() {
        $this->db = Database::getInstance(Config::DB_INSTANCE);
    }

    public function register($username, $password) {
        if($this->exists($username)) {
            throw new \Exception("User already registered");
        }

        $result = $this->db->prepare("
            INSERT INTO users (username, password_hash)
            VALUES (?, ?)
        ");

        $result->execute([
            $username,
            password_hash($password, PASSWORD_DEFAULT),
        ]);

        if($result->rowCount() > 0) {
            return true;
        }

        throw new \Exception("Cannot register user");
    }


    private function exists($username) {
        $result = $this->db->prepare("
            SELECT id FROM users WHERE username = ?
        ");

        $result->execute([
            $username
        ]);

        if($result->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function login($username, $password) {
        $result = $this->db->prepare("
            SELECT * FROM users WHERE username = ?
        ");

        $result->execute([
            $username
        ]);

        if($result->rowCount() <= 0) {
            throw new \Exception("Invalid username");
        }

        $userRow = $result->fetch();

        if(password_verify($password, $userRow['password_hash'])) {
            return $userRow['id'];
        } else {
            throw new \Exception("Password not match");
        }
    }
}