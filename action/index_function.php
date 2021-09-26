<?php

require_once '../action/db_controller.php';

// DUBUG
// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";

$dbh = new DbController();

// 登録画面送信データの登録を行う
/**
 * DB INSERT処理
 */
if(isset($_POST['insert'])){
  $dbh->insert_tasks($_POST);
  header("Location: ../view/index.php");
  exit();
}

/**
 * DB UPDATE処理
 */
if(isset($_POST['update'])){
  $dbh->update_tasks($_POST);
  header("Location: ../view/index.php");
  exit();
}

/**
 * DB DELETE処理
 */
if(isset($_POST['delete'])){
  $dbh->delete_tasks($_POST);
  header("Location: ../view/index.php");
  exit();
}

?>