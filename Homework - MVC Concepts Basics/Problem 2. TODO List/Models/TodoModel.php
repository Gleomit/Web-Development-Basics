<?php

namespace Framework\Models;

use Framework\Config\Config;
use Framework\Library\BaseModel;
use Framework\Library\Database;

class TodoModel extends BaseModel
{
    public function __construct() {
        $this->db = Database::getInstance(Config::DB_INSTANCE);
    }

    public function getTodos($userId) {
        $result = $this->db->prepare("
            SELECT id, todo_item FROM todos WHERE user_id = ?
        ");

        $result->execute([$userId]);

        $todos = $result->fetchAll();

        return $todos;
    }

    public function add($userId, $todoText) {
        $result = $this->db->prepare("
            INSERT INTO todos(user_id, todo_item)
            VALUES (?, ?)
        ");

        $result->execute([$userId, $todoText]);

        if($result->rowCount() > 0) {
            return true;
        }

        throw new \Exception("Cannot add todo(unknown error");
    }

    public function delete($userId, $todoId) {
        $todo = $this->db->prepare("
            SELECT user_id FROM todos WHERE id = ?
        ");

        $todo->execute([$todoId]);

        if($todo->rowCount() < 0) {
            throw new \Exception("Todo not found");
        }

        $todoItem = $todo->fetch();

        if($userId != $todoItem['user_id']) {
            throw new \Exception("You are not the owner");
        }

        $result = $this->db->prepare("
            DELETE FROM todos WHERE id = ?"
        );

        $result->execute([$todoId]);

        if($result->rowCount() > 0) {
            return true;
        }

        throw new \Exception("Cannot delete todo(unknown error)");
    }
}