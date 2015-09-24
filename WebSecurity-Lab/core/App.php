<?php

namespace Core;

class App
{
    /**
     * @var Database
     */
    private $db;

    /**
     * @var User
     */
    private $user;

    /**
     * @var BuildingsRepository
     */
    private $buildingsRepository;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getUserInfo($id) {
        $result = $this->db->prepare("
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

    /**
     * return User|null
     */
    public function getUser() {
        if($this->user != null) {
            return $this->user;
        }

        if($this->isLogged()) {
            $userRow = $this->getUserInfo($_SESSION['id']);

            $this->user = new User(
                $userRow['username'],
                $userRow['password'],
                $userRow['id'],
                $userRow['gold'],
                $userRow['food']
            );

            return $this->user;
        }

        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function editUser(User $user) {
        $result = $this->db->prepare("
            UPDATE users SET password = ?, username = ? WHERE id = ?
        ");

        var_dump($user);

        $result->execute([
            password_hash($user->getPass(), PASSWORD_DEFAULT),
            $user->getUsername(),
            $user->getId()
        ]);

        return $result->rowCount() > 0;
    }

    /**
     * @return bool
     */
    public function isLogged() {
        return isset($_SESSION['id']);
    }

    /**
     * @param $username
     * @return bool
     */
    public function userExists($username) {
        $result = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $result->execute([$username]);

        return $result->rowCount() > 0;
    }

    /**
     * @return BuildingsRepository
     */
    public function createBuildings() {
        if($this->buildingsRepository == null) {
            $this->buildingsRepository = new BuildingsRepository($this->db, $this->getUser());
        }

        return $this->buildingsRepository;
    }

    public function login($username, $password) {
        $result = $this->db->prepare("
            SELECT * FROM users WHERE username = ?
        ");

        $result->execute([$username]);

        if($result->rowCount() <= 0) {
            throw new \Exception("Invalid username");
        }

        $userRow = $result->fetch();

        if(password_verify($password, $userRow['password'])) {
            $_SESSION['id'] = $userRow['id'];

            $this->user = new User(
                $userRow['username'],
                $userRow['password'],
                $userRow['id'],
                $userRow['gold'],
                $userRow['food']);

            return true;
        } else {
            throw new \Exception("Password not match");
        }
    }

    public function register($username, $password) {
        if($this->userExists($username)) {
            throw new \Exception("User already registered");
        }

        $result = $this->db->prepare("
            INSERT INTO users (id, username, password, gold, food)
            VALUES (NULL, ?, ?, ?, ?);
        ");

        $data = [
            $username,
            password_hash($password, PASSWORD_DEFAULT),
            User::GOLD_DEFAULT,
            User::FOOD_DEFAULT
        ];

        $result->execute($data);

        if($result->rowCount() > 0) {
            $userId = $this->db->lastId();

            $this->db->query("
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
}