<?php 
	require_once 'constants.php';

	class todo_db {
		private $DBH = null;

		private $hostname = Constants::hostname;

		private $db_name = Constants::db_name;

		public function __construct() {
			try {
				$this->DBH = new PDO("mysql:host=$this->hostname;dbname=$this->db_name",
					Constants::username, Constants::password);
			} catch (Exception $e) {
				echo $e->getMessage();
			}
		}

		public function __destruct() {
			$this->close_db();
		}

		public function createUser($username, $password) {
			$STH = $this->DBH->prepare("SELECT username FROM users WHERE username = :username");

			$data = array('username' => $username);

			$STH->execute($data);

			if($STH->rowCount() > 0) {
				return array('message' => "There is already a user with this username..");
			}

			$STH = $this->DBH->prepare("INSERT INTO users (username, passwordHash)
										VALUES (:username, :password)");

			$data = array(':username' => $username, ':password' => password_hash($password, PASSWORD_DEFAULT));

			if($STH->execute($data)) {
				return true;
			}

			return array('message' => "Something went wrong during the registration.");
		}

		public function isUserValid ($username, $password) {
			$STH = $this->DBH->prepare("SELECT * FROM users WHERE username = :username");

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
			$STH = $this->DBH->prepare("SELECT id, todo_item FROM todos WHERE user_id = :user_id");

			$data = array(':user_id' => $user_id);

			$STH->execute($data);

			$todo_items = $STH->fetchAll(PDO::FETCH_ASSOC);

			return $todo_items;
		}

		public function addTodoItem($user_id, $todo_text) {
			$STH = $this->DBH->prepare("INSERT INTO todos (user_id, todo_item)
										VALUES (:user_id, :todo_text)");

			$data = array(':user_id' => $user_id,
						  ':todo_text' => $todo_text);

			if($STH->execute($data)) {
				return true;
			}

			return false;
		}

		public function deleteTodoItem($user_id, $todo_id) {
			$STH = $this->DBH->prepare("SELECT user_id FROM todos WHERE user_id = :user_id
										AND id = :todo_id");

			$data = array(':user_id' => $user_id,
				':todo_id' => $todo_id);

			$STH->execute($data);

			if($STH->rowCount() > 0) {
				$STH = $this->DBH->prepare("DELETE FROM todos
 												WHERE id = :todo_id AND user_id = :user_id");

				if($STH->execute($data)) {
					return true;
				}
			}

			return false;
		}	

		private function close_db() {
			if($this->DBH != null) {
				$this->DBH = null;
			}
		}
	}
?>