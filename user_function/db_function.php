<?php
class DbFunction{

	public function db_conection(){
		try{
			$pdo = new PDO('sqlite:Login.sqlite3');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			echo('--- db conection ok ---' . "\n");
			return $pdo;
		}catch (Exception $e){
			echo ($e->getMessage());
		}
	}
	
	public function db_create_users_table(){
		$dbh = $this->db_conection();
		$sql = "CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY AUTOINCREMENT,name VARCHAR(10),pass TEXT, mail TEXT)";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}

	public function db_create_tasks_table(){
		$dbh = $this->db_conection();
		$sql = "CREATE TABLE IF NOT EXISTS tasks(id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, title VARCHAR(10), body VARCHAR(30), done INTEGER)";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}
	
	//TODO パスワード保存する際はハッシュ保存とするべき
	public function db_insert_default_user(){
		$dbh = $this->db_conection();
		$sql = "INSERT INTO users(name, pass, mail) VALUES ('dev_user', 'dev_user', 'dev_user@test.com')";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}

	public function db_select($sql){
		$dbh = $this->db_conection();
		//$sql = "SELECT * FROM users";
		//var_dump($sql);
		$stmt = $dbh->query($sql);
    	$items = $stmt->fetchAll();
    	return $items;
	}

	//不要
	public function db_select_users(){
		$dbh = $this->db_conection();
		$sql = "SELECT * FROM users";
		//var_dump($sql);
		$stmt = $dbh->query($sql);
    	$items = $stmt->fetchAll();
    	return $items;
	}

	//不要
	public function db_select_tasks(){
		$dbh = $this->db_conection();
		$sql = "SELECT * FROM tasks";
		//var_dump($sql);
		$stmt = $dbh->query($sql);
    	$items = $stmt->fetchAll();
    	return $items;
	}
}

?>