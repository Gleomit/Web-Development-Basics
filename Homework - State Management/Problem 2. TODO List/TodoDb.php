<?php 
	require_once 'DbConfig.php';

	class TodoDb {
		private $_connection = null;

		private $_hostname = DbConfig::hostname;

		private $_db_name = DbConfig::db_name;

		public function __construct() {
			try {
				$this->_connection = new PDO("mysql:host=$this->_hostname;dbname=$this->_db_name",
					DbConfig::username, DbConfig::password);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public function __destruct() {
			$this->close_db();
		}

		public function createUser($username, $password) {
			$STH = $this->_connection->prepare("SELECT username FROM users WHERE username = :username");

			$data = array('username' => $username);

			$STH->execute($data);

			if($STH->rowCount() > 0) {
				return array('message' => "There is already a user with this username..");
			}

			$STH = $this->_connection->prepare("INSERT INTO users (username, passwordHash)
										VALUES (:username, :password)");

			$data = array(':username' => $username, ':password' => password_hash($password, PASSWORD_DEFAULT));

			if($STH->execute($data)) {
				return true;
			}

			return array('message' => "Something went wrong during the registration.");
		}

		public function isUserValid ($username, $password) {
			$STH = $this->_connection->prepare("SELECT * FROM users WHERE username = :username");

			$data = array(':username' => $username);

			if($STH->execute($data)) {
				if($STH->rowCount() > 0) {
					$result = $STH->fetch(PDO::FETCH_ASSOC);

					if(password_verify($password, $result['passwordHash'])) {
						return array('user_id' => $result['id'], 'username' => $result['username']);
					}
				}
			}

			return false;
		}

		public function getTodoItems($user_id) {
			$STH = $this->_connection->prepare("SELECT id, todo_item FROM todos WHERE user_id = :user_id");

			$data = array(':user_id' => $user_id);

			$STH->execute($data);

			$todo_items = $STH->fetchAll(PDO::FETCH_ASSOC);

			return $todo_items;
		}

		public function addTodoItem($user_id, $todo_text) {
			$STH = $this->_connection->prepare("INSERT INTO todos (user_id, todo_item)
										VALUES (:user_id, :todo_text)");

			$data = array(':user_id' => $user_id,
						  ':todo_text' => $todo_text);

			if($STH->execute($data)) {
				return true;
			}

			return false;
		}

		public function deleteTodoItem($user_id, $todo_id) {
			$STH = $this->_connection->prepare("SELECT user_id FROM todos WHERE user_id = :user_id
										AND id = :todo_id");

			$data = array(':user_id' => $user_id,
				':todo_id' => $todo_id);

			$STH->execute($data);

			if($STH->rowCount() > 0) {
				$STH = $this->_connection->prepare("DELETE FROM todos
 												WHERE id = :todo_id AND user_id = :user_id");

				if($STH->execute($data)) {
					return true;
				}
			}

			return false;
		}	

		private function close_db() {
			if($this->_connection != null) {
				$this->_connection = null;
			}
		}
	}