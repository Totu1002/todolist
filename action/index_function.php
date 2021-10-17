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

/**
 * DB UPDATE処理
 * user passwordを更新する
 */
if (isset($_POST['update_password'])){
  $user = $dbh->select_users_id($_POST[':id']);
  $user = $user[0];

  if(empty($_POST['current_password']) || empty($_POST["new_password"]) || empty($_POST["again_new_password"])) {
    $message = 'The input field is blank';
    header("Location: ../view/change_password.php?message=$message");
    exit();
  }

  if (password_verify($_POST['current_password'], $user['pass'])){
    if ($_POST['new_password'] === $_POST['again_new_password']){
      $dbh->update_users_password($_POST['new_password'],$_POST[':id']);
      header("Location: ../view/index.php");
      exit();
    }else{
      $message = 'New password does not match';
      header("Location: ../view/change_password.php?message=$message");
      exit();
    }
  } else {
    $message = 'Password is incorrect';
    header("Location: ../view/change_password.php?message=$message");
    exit();
  }
}

?>