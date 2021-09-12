<?php

class DbConection{
    
    public function __construct(){
      //config.iniファイルを定義する
    }

    public function db_conect(){
      $dbuser='postgres'; //config.iniから参照するようにする
      $dbname='todolist';
      $dbpass = '';
      $dbhost='localhost';
      $dbport='5432';
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
        var_dump("Successful connection");
      } catch (PDOException $e) {
        var_dump("Connection failed");
        exit($e->getMessage());
      }
      return $dbh;
    }

    public function db_insert($db_value){
      $sql = 'INSERT INTO tasks(title, body, done) VALUES (:title, :body, :done)';
      $dbh = $this->db_conect();
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":title", $db_value[':title']);
      $stmt->bindValue(":body", $db_value[':body']);
      $stmt->bindValue(":done", $db_value[':done']);
      $stmt->execute();
      $dbh = null;
    }

    //全件取得SELECT文
    function db_select_all(){
      $sql = 'SELECT * FROM tasks';
      $dbh = $this->db_conect();
      $stmt = $dbh->query($sql);
      $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $dbh = null;
      return $items;
    }

    //SELECT文のときに使用する関数。
    function db_select_show($id){
      $sql = "SELECT * FROM tasks WHERE id={$id}";
      $dbh = $this->db_conect();
      $stmt = $dbh->query($sql);
      $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $dbh = null;
      return $items;
    }

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
    }

    public function db_delete($db_value){
      $sql = "DELETE FROM tasks WHERE id=:id";
      $dbh = $this->db_conect();
      $stmt = $dbh->prepare($sql);
      $stmt->bindValue(":id", $db_value[':id']);
      $stmt->execute();
      $dbh = null;
    }
  }

//TEST CODE
//以下記述はindexにて呼び出す
//$_POSTを直接引数に指定する
//$db_value = array();
//$db_value[':title'] = 'test5'; //テスト用の値を指定
//$db_value[':body'] = 'test5';
//$db_value[':done'] = 1; // デフォルトで1を代入する
//var_dump($db_value);
//
//$db_conect = new DbConection;
////$db_conect->db_conect();
//$db_conect->db_insert($db_value);
//$res_db_select = $db_conect->db_select();
//var_dump($res_db_select);

?>