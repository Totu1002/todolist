<?php
class DbFunction{

	public function db_conection(){
		try{
			$pdo = new PDO('sqlite:Login.sqlite3');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			//echo('ok');
			return $pdo;
		}catch (Exception $e){
			echo ($e->getMessage());
		}
	}
	
	public function db_create_table(){
		$dbh = $this->db_conection();
		$sql = "CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY AUTOINCREMENT,name VARCHAR(10),pass TEXT, mail TEXT)";
		$stmt = $dbh->prepare($sql);
    	$stmt->execute();
	}
	
	public function db_select(){
		$dbh = $this->db_conection();
		$sql = "SELECT * FROM users";
		//var_dump($sql);
		$stmt = $dbh->query($sql);
    	$items = $stmt->fetchAll();
    	return $items;
	}
}

?>