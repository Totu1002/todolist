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
    $this->ini_file = parse_ini_file('./config/config.ini', true);
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
   */
  public function db_insert($db_value){
    $sql = 'INSERT INTO tasks(title, body, done) VALUES (:title, :body, :done)';
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":title", $db_value[':title']);
    $stmt->bindValue(":body", $db_value[':body']);
    $stmt->bindValue(":done", $db_value[':done']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':title'] . " " . $db_value[':body'];
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB SELECT処理
   * index用全件取得
   */
  function db_select_all(){
    $sql = 'SELECT * FROM tasks';
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
   * 特定レコード取得
   */
  function db_select_id($id){
    $sql = "SELECT * FROM tasks WHERE id={$id}";
    $dbh = $this->db_conect();
    $stmt = $dbh->query($sql);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB UPDATE処理
   */
  public function db_update($db_value){
    //var_dump($db_value);
    $sql = "UPDATE tasks SET title=:title, body=:body, done=:done WHERE id={$db_value[':id']}";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":title", $db_value[':title']);
    $stmt->bindValue(":body", $db_value[':body']);
    $stmt->bindValue(":done", $db_value[':done']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__ . " " . $db_value[':title'] . " " . $db_value[':body'];
    $this->log_file->record_logging($log_msg);
  }

  /**
   * DB DELETE処理
   */
  public function db_delete($db_value){
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
  /**
   * signin.phpにて使用
   * session管理、users/idを取得
   */
	public function select_signin_user($db_value){
		$dbh = $this->db_conect();
		$sql = "SELECT * FROM users WHERE name=:name";
		//var_dump($sql);
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':name',$db_value); // TODO nameによる参照は不適切かも
		$stmt->execute();
    $items = $stmt->fetchAll();
    return $items;
	}  


  // 以下、検証/動作確認用メソッド群につき、最終的には削除予定
  /**
   * sqlite3へ接続する場合の処理
   * 動作確認/検証用
   * 使用する場合には他メソッドの$dbhを定義している箇所を本メソッドに置き換える
   */
	public function db_conect_sqlite3(){
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
   */
  public function create_users_table(){
		$dbh = $this->db_conect();
    //sqlite用
		//$sql = "CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY AUTOINCREMENT,name VARCHAR(10),pass TEXT, mail TEXT)";
    //postgresql用
    $sql = "CREATE TABLE IF NOT EXISTS users(id SERIAL,name VARCHAR(10),pass TEXT, mail TEXT, PRIMARY KEY (id))";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}

	public function create_tasks_table(){
		$dbh = $this->db_conect();
    //sqlite用
		//$sql = "CREATE TABLE IF NOT EXISTS tasks(id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, title VARCHAR(10), body VARCHAR(30), done INTEGER)";
    //postgresql用
    $sql = "CREATE TABLE IF NOT EXISTS tasks(id SERIAL, user_id INTEGER, title VARCHAR(10), body VARCHAR(30), done INTEGER, PRIMARY KEY (id))";
		$stmt = $dbh->prepare($sql);
    $stmt->execute();
	}

  /**
   * 検証用ユーザー作成処理
   */
	public function insert_default_user(){
		$dbh = $this->db_conect();
		$user_name = "dev_user";
		$user_pass = password_hash('dev_user_pass', PASSWORD_DEFAULT);
		$user_mail = "dev_user@tes.com";
		$sql = "INSERT INTO users(name, pass, mail) VALUES (:name, :pass, :mail)";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':name',$user_name);
		$stmt->bindParam(':pass',$user_pass);
		$stmt->bindParam(':mail',$user_mail);
    $stmt->execute();
	}

	//検証用:usersテーブル全件取得メソッド
	public function select_users_all(){
		$dbh = $this->db_conect();
		$sql = "SELECT * FROM users";
		$stmt = $dbh->query($sql);
    $items = $stmt->fetchAll();
    return $items;
	}

	//検証用:tasksテーブル全件取得メソッド
	public function select_tasks_all(){
		$dbh = $this->db_conect();
		$sql = "SELECT * FROM tasks";
		$stmt = $dbh->query($sql);
    $items = $stmt->fetchAll();
    return $items;
	}
}
?>