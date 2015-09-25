<?php

namespace SoftUni\Models;

use SoftUni\Config\DatabaseConfig;
use SoftUni\Core\Database;
use SoftUni\Helpers\Session;

class User
{
    const GOLD_DEFAULT = 1500;
    const FOOD_DEFAULT = 1500;

    public function register($username, $password) {
        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        if($this->exists($username)) {
            throw new \Exception("User already registered");
        }

        $result = $db->prepare("
            INSERT INTO users (username, password, gold, food)
            VALUES (?, ?, ?, ?)
        ");

        $result->execute([
            $username,
            password_hash($password, PASSWORD_DEFAULT),
            self::GOLD_DEFAULT,
            self::FOOD_DEFAULT
        ]);

        if($result->rowCount() > 0) {
            $userId = $db->lastId();

            $db->query("
                INSERT INTO user_buildings (user_id, building_id, level_id)
                SELECT $userId, b.id, bl.id  FROM buildings b
                INNER JOIN (
                    SELECT id, building_id, MIN(level) FROM building_levels
                ) bl ON bl.building_id = b.id
            ");

            return true;
        }

        throw new \Exception("Cannot register user");
    }

    public function edit($username, $password, $confirmPassword) {
        if(!$this->exists($username)) {
            throw new \Exception("User does not exists");
        }

        if($password != $confirmPassword) {
            throw new \Exception("Passwords does not match");
        }

        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        $result = $db->prepare("
            UPDATE users SET password = ? WHERE username = ?
        ");

        $result->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $username
        ]);

        if($result->rowCount() > 0) {
            return true;
        }

        throw new \Exception("Database error");
    }

    private function exists($username) {
        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        $result = $db->prepare("
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

    public function getInfo($id) {
        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        $result = $db->prepare("
            SELECT
                id, username, password, gold, food
            FROM
              users
            WHERE id = ?
        ");

        $result->execute([$id]);

        $userInfo = $result->fetch();

        return $userInfo;
    }

    public function getBuildings() {
        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        $result = $db->prepare("
            SELECT  b.id, b.name, bl.level,
              (SELECT gold FROM building_levels WHERE building_id = ub.building_id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS gold,
              (SELECT food FROM building_levels WHERE building_id = ub.building_id AND level = (SELECT level FROM building_levels WHERE id = ub.level_id) + 1) AS food
            FROM user_buildings as ub
            INNER JOIN buildings AS b ON b.id = ub.building_id
            INNER JOIN building_levels AS bl ON bl.id = ub.level_id
            WHERE ub.user_id = ?;
        ");

        $result->execute([Session::get('id')]);

        $data = $result->fetchAll();

        return $data;
    }

    public function login($username, $password) {
        $db = Database::getInstance(DatabaseConfig::DB_INSTANCE);

        $result = $db->prepare("
            SELECT * FROM users WHERE username = ?
        ");

        $result->execute([
            $username
        ]);

        if($result->rowCount() <= 0) {
            throw new \Exception("Invalid username");
        }

        $userRow = $result->fetch();

        if(password_verify($password, $userRow['password'])) {
            return $userRow['id'];
        } else {
            throw new \Exception("Password not match");
        }
    }
}