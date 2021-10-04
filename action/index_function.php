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
 * taskに新規登録を行う
 */
if(isset($_POST['insert'])){
  $dbh->insert_tasks($_POST);
  header("Location: ../view/index.php");
  exit();
}

/**
 * DB UPDATE処理
 * taskを更新する
 */
if(isset($_POST['update_task'])){
  $dbh->update_tasks($_POST);
  header("Location: ../view/index.php");
  exit();
}

/**
 * DB UPDATE処理
 * taskを完了へ変更する
 */
if(isset($_POST['done'])){
  $dbh->update_tasks_done($_POST);
  header("Location: ../view/index.php");
  exit();
}

/**
 * DB DELETE処理
 * taskを削除する
 */
if(isset($_POST['delete'])){
  $dbh->delete_tasks($_POST);
  header("Location: ../view/index.php");
  exit();
}

/**
 * DB UPDATE処理
 * userを更新する
 */
if(isset($_POST['update_user'])){
  $dbh->update_users($_POST);
  header("Location: ../view/index.php");
  exit();
}

?>