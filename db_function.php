<?php
require_once 'logging.php';
/**
 * DB操作用クラス
 */
class DbConection{

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
      $log_msg = "データベースへの接続に成功しました。";
      $this->log_file->record_logging($log_msg);
    } catch (PDOException $e) {
      $log_msg = "データベースへの接続に失敗しました。";
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
}
?>