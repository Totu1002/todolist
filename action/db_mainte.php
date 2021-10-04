<?php
require_once 'logging.php';
/**
 * DB操作用クラス
 */
class DbMainteController{

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
   * 
   */
  public function select_users_default(){
    $role = 2;
    $sql = "SELECT * FROM users WHERE role = :role";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":role", $role);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;
    return $items;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  public function delete_users_defualt(){
    $role = 2;
    $sql = "DELETE FROM users WHERE role=:role";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":role", $role);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }

  public function delete_tasks_defualt($user_id){
    $sql = "DELETE FROM tasks WHERE user_id=:user_id";
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":user_id", $user_id);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
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

  /**
   * デフォルトユーザー作成処理
   * 繰り返し処理にて複数ユーザーを作成する想定
   */
  public function insert_users_default($name,$mail,$pass,$role){
    $dbh = $this->db_conect();
    $sql = "INSERT INTO users(name, mail, pass,role) VALUES (:name, :mail, :pass, :role)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':mail', $mail);
    $stmt->bindValue(':pass', $pass);
    $stmt->bindValue(':role', $role);
    $stmt->execute();
  }

  /**
   * デフォルトユーザー用投稿処理
   */
  public function insert_tasks_default($user_id){
    $task_param = $this->ini_file['DefaultTasksDate'];
    $sql = 'INSERT INTO tasks(user_id, title, body) VALUES (:user_id, :title, :body)';
    $dbh = $this->db_conect();
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(":user_id", $user_id); 
    $stmt->bindValue(":title", $task_param['DefaultTitle']);
    $stmt->bindValue(":body", $task_param['DefaultBody']);
    $stmt->execute();
    $dbh = null;
    $log_msg = __FUNCTION__;
    $this->log_file->record_logging($log_msg);
  }  

  public function delete_default_all(){
    //デフォルトユーザー(role:2)を取得
    $defult_users = $this->select_users_default();
  
    //DEBUG
    //var_dump($defult_users);

    foreach ($defult_users as $defualt_user) {
      //ユーザーに紐づくtaskを削除
      var_dump($defualt_user['id']);
      $this->delete_tasks_defualt($defualt_user['id']);
    }

    //ゲスト/デフォルトユーザー(role:2)を削除
    $this->delete_users_defualt();
  }

  public function create_default_all(){
    //管理者追加　この処理は特に使用しない
    //$this->insert_users_administrator();

    //ゲストユーザー追加
    $this->insert_users_guest();

    //デフォルトユーザー追加
    for ($count = 1; $count < 11; $count++){
      $name = "user" . $count;
      $mail = $name . "@test.com";
      $pass = password_hash($name . "pass", PASSWORD_DEFAULT);
      $role = 2; //guest,defaultユーザー削除

      //var_dump($name);
      //var_dump($mail);
      //var_dump($pass);
      //var_dump($role);

      $this-> insert_users_default($name,$mail,$pass,$role);  
    }

    //新規作成したデフォルトユーザーIDを取得し、デフォルトtask作成時のuser_idカラムに代入する
    $new_defult_users = $this->select_users_default();
    foreach ($new_defult_users as $defualt_user) {
      //var_dump($defualt_user['id']);
      for ($count = 1; $count < 6; $count++){
        $this->insert_tasks_default($defualt_user['id']);
      }
    }
  }
}

/**
 * デフォルトユーザー/タスクのメンテナンス処理
 * 1.role:2のユーザー情報を取得
 * 2.ユーザーに紐づくタスクを削除
 * 3.ユーザーを削除
 * 3.ゲスト/デフォルトユーザーを新規作成
 * 4.作成したユーザーに紐づくデフォルトtaskを作成
 */

$db_mainte = new DbMainteController;

//$db_mainte->delete_default_all();
$db_mainte->create_default_all();



?>