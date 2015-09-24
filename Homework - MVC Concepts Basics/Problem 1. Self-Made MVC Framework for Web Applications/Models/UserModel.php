<?php

namespace Models;

use Config\Config;
use Library\Database;
use Library\BaseModel;

class UserModel extends BaseModel
{
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($username, $password) {
        $statement = $this->db->prepare("
            SELECT id, password FROM Users WHERE username = ?
        ");

        $data = [$username];

        $statement->execute($data);

        if($statement->rowCount() > 0) {
            $userRow = $statement->fetch(\PDO::FETCH_ASSOC);

            if(password_verify($password, $userRow['password'])) {
                $_SESSION['userId'] = $userRow['id'];

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function register($username, $password) {
        if($this->userExists($username)) {
            return false;
        }

        $statement = $this->db->prepare("
            INSERT INTO Users (id, username, password)
            VALUES (NULL, ?, ?)
        ");

        $data = [
            $username,
            password_hash($password, PASSWORD_DEFAULT)
        ];

        if($statement->execute($data)) {
            if(Config::AUTO_LOGIN_ON_REGISTER) {
                $this->login($username, $password);
            }

            return true;
        } else {
            return false;
        }
    }

    public function userExists($username) {
        $statement = $this->db->prepare("
            SELECT id FROM Users WHERE username = ?
        ");

        $data = [$username];

        $statement->execute($data);

        if($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}