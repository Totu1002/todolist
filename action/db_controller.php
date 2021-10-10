<?php
require_once 'logging.php';
/**
 * DB操作用クラス
 */
class DbController{

  private $ini_file;
  private $log_file;

  /**
   * コンストラクタ
   */
  public function __construct(){
    $this->ini_file = parse_ini_file('../config/config.ini', true);
    $this->log_file = new RecordLogging;
  }

  public function db_conect(){
    $db_param = $this->ini_file['DbParam'];
    $dbuser = $db_param['DbUser'];
    $dbname = $db_param['DbName'];
    $dbpass = $db_param['DbPass'];
    $dbhost = $db_param['DbHost'];
    $dbport = $db_param['DbPort'];
    //$dsn="pgsql:host={$dbhost};port={$dbport};dbname={$dbname};";
    $dsn="pgsql:port={$dbport};dbname={$dbname};";
    try {
      $dbh = new PDO(
        $dsn,
        $dbuser,
        $dbpass,
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_EMULATE_PREPARES => false
        ]
      );  
      $log_msg = __FUNCTION__ . " " . "Successful connection to the database";
      $this->log_file->record_logging($log_msg);
    } catch (PDOException $e) {
      $log_msg = __FUNCTION__ . " " . "Failed to connect to database";
      $this->log_file->record_logging($log_msg);
      exit($e->getMessage());
    }
    return $dbh;
  }

  /**
   * DB INSERT処理
   * tasksテーブル用
   */
  public function insert_tasks($db_value){
    $sql = 'INSERT INTO tasks(user_id, title, body) VALUES (:user_id, :title, :body)';
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":user_id", $db_value[':user_id']);
    $stmt->bindValue(":title", $db_value[':title']);
    $stmt->bindValue(":body", $db_value[':body']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':title'] . " " . $db_value[':body'];
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB INSERT処理
   * usersテーブル用
   */
  public function insert_users($db_value){
    $sql = "INSERT INTO users(name, mail, pass) VALUES (:name, :mail, :pass)";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':name', $db_value['name']);
    $stmt->bindValue(':mail', $db_value['mail']);
    $stmt->bindValue(':pass', password_hash($_POST['pass'], PASSWORD_DEFAULT));
    
    $stmt->execute();
  }

  /**
   * DB SELECT処理
   * users全件取得
   */
  public function select_users_all(){
    $sql = 'SELECT * FROM users';
    $dbh = $this->db_conect();
    $stmt = $dbh->query($sql);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB SELECT処理
   * index用userに紐づくtask全件取得
   */
  public function select_user_task($user_id,$done){
    //$sql = "SELECT * FROM tasks  JOIN users ON tasks.userid = users.id WHERE users.id = {$db_value}";
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id AND done = :done";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam( ':user_id', $user_id);
    $stmt->bindParam( ':done', $done);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }


  /**
   * DB SELECT処理
   * tasksテーブル特定レコード取得
   */
  public function select_tasks_id($task_id,$user_id){
    $sql = "SELECT * FROM tasks WHERE id = :id AND user_id = :user_id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":id", $task_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB SELECT処理
   * user idを基にusersテーブル特定レコード取得
   */
  public function select_users_id($id){
    $sql = "SELECT * FROM users WHERE id = :id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB SELECT処理
   * user nameを基にusersテーブル特定レコード取得
   */
  public function select_users_name($user_name){
    $sql = "SELECT * FROM users WHERE name = :name";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":name", $user_name);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB SELECT処理
   * user maileを基にusersテーブル特定レコード取得
   */
  public function select_users_mail($user_mail){
    $sql = "SELECT * FROM users WHERE mail = :mail";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":mail", $user_mail);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB UPDATE処理
   * taskを更新する
   */
  public function update_tasks($db_value){
    //var_dump($db_value);
    $sql = "UPDATE tasks SET title=:title, body=:body WHERE id=:id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $db_value[':id']);
    $stmt->bindValue(":title", $db_value[':title']);
    $stmt->bindValue(":body", $db_value[':body']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':title'] . " " . $db_value[':body'];
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB UPDATE処理
   * userを更新する
   */
  public function update_users($db_value){
    //var_dump($db_value);
    $sql = "UPDATE users SET name=:name, mail=:mail WHERE id=:id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $db_value[':id']);
    $stmt->bindValue(":name", $db_value[':name']);
    $stmt->bindValue(":mail", $db_value[':mail']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':name'] . " " . $db_value[':mail'];
    $this->log_file->record_logging($log_msg);
  }

  public function update_users_status($user_status,$user_id){
    //var_dump($db_value);
    $sql = "UPDATE users SET status=:status WHERE id=:id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $user_id); //sessionから取得
    $stmt->bindValue(":status", $user_status);

    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $user_id . " " . $user_status[':status'];
    $this->log_file->record_logging($log_msg);
  }

    /**
   * DB UPDATE処理
   * taskを完了へと変更する
   */
  public function update_tasks_done($db_value){
    //var_dump($db_value);
    $sql = "UPDATE tasks SET done=:done WHERE id = :id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $db_value[':id']);
    $stmt->bindValue(":done", $db_value[':done']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':id'];
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB DELETE処理
   * taskを削除する
   */
  public function delete_tasks($db_value){
    $sql = "DELETE FROM tasks WHERE id=:id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":id", $db_value[':id']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':id'];
    $this->log_file->record_logging($log_msg);
  }

  // TODO 以下、user_function branchから反映内容

  // 以下、検証/動作確認用メソッド群につき、最終的には削除予定
  /**
   * sqlite3へ接続する場合の処理
   * 動作確認/検証用
   * 使用する場合には他メソッドの$dbhを定義している箇所を本メソッドに置き換える
   */
	public function db_conect_sqlite3(){
		try{
			$pdo = new PDO('sqlite:../data/Login.sqlite3');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			echo('--- db conection ok ---' . "\n");
			return $pdo;
		}catch (Exception $e){
			echo ($e->getMessage());
		}
	}

  /**
   * 検証用メソッド
   * DBよりテーブルを削除する処理
   */
  public function delete_table_tasks(){
		$dbh = $this->db_conect();
		$sql = "DROP TABLE tasks";
		$stmt = $dbh->query($sql);
    $items = $stmt->fetchAll();
    return $items;
	}

  public function delete_table_users(){
		$dbh = $this->db_conect();
		$sql = "DROP TABLE users";
		$stmt = $dbh->query($sql);
    $items = $stmt->fetchAll();
    return $items;
	}

  /**
   * 新規テーブル/カラム作成処理
   * role 0:ユーザー,1:管理者,2:ゲスト
   * status 0:入会,1:退会
   */
  public function create_users_table(){
		$dbh = $this->db_conect();
    //sqlite用
		//$sql = "CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY AUTOINCREMENT,name VARCHAR(10),pass TEXT, mail TEXT)";
    //postgresql用
    $sql = "CREATE TABLE users(id SERIAL,name VARCHAR(10),pass TEXT, mail TEXT,role INTEGER default 0, status BOOLEAN default 'TRUE', PRIMARY KEY (id))";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}

	public function create_tasks_table(){
		$dbh = $this->db_conect();
    //sqlite用
		//$sql = "CREATE TABLE IF NOT EXISTS tasks(id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, title VARCHAR(10), body VARCHAR(30), done INTEGER)";
    //postgresql用
    $sql = "CREATE TABLE tasks(id SERIAL, user_id INTEGER, title VARCHAR(100), body VARCHAR(100), done BOOLEAN default 'TRUE', PRIMARY KEY (id))";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}

  /**
   * 管理者ユーザー作成処理
   */
	public function insert_users_administrator(){
		$dbh = $this->db_conect();
		$user_name = "admin";
		$user_pass = password_hash('adminpass', PASSWORD_DEFAULT);
		$user_mail = "admin@admin.com";
    $user_role = 1; //0:user,1:admin,2:guest
		$sql = "INSERT INTO users(name, pass, mail, role) VALUES (:name, :pass, :mail, :role)";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':name',$user_name);
		$stmt->bindParam(':pass',$user_pass);
		$stmt->bindParam(':mail',$user_mail);
    $stmt->bindParam(':role',$user_role);
    $stmt->execute();
	}

  /**
   * ゲストユーザー作成処理
   */
	public function insert_users_guest(){
		$dbh = $this->db_conect();
		$user_name = "guest";
		$user_pass = password_hash('guestpass', PASSWORD_DEFAULT);
		$user_mail = "guest@guest.com";
    $user_role = 2; //0:user,1:admin,2:guest
		$sql = "INSERT INTO users(name, pass, mail, role) VALUES (:name, :pass, :mail, :role)";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':name',$user_name);
		$stmt->bindParam(':pass',$user_pass);
		$stmt->bindParam(':mail',$user_mail);
    $stmt->bindParam(':role',$user_role);
    $stmt->execute();
	}

	//検証用:usersテーブル全件取得メソッド
	public function select_users_all_dev(){
		$dbh = $this->db_conect();
		$sql = "SELECT * FROM users";
		$stmt = $dbh->query($sql);
    $items = $stmt->fetchAll();
    return $items;
	}

	//検証用:tasksテーブル全件取得メソッド
	public function select_tasks_all_dev(){
		$dbh = $this->db_conect();
		$sql = "SELECT * FROM tasks";
		$stmt = $dbh->query($sql);
    $items = $stmt->fetchAll();
    return $items;
	}
}
?>