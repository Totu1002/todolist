<?php
require_once '../action/db_controller.php';

// DUBUG
echo "<pre>";
var_dump($_POST);
echo "</pre>";

//セッションを使うことを宣言
session_start();

$dbh = new DbController;


//postされて来なかったとき
if (count($_POST) === 0) {
  $message = "";
}
//postされて来た場合
else {
  //ユーザー名またはパスワードが送信されて来なかった場合
  if(empty($_POST["name"]) || empty($_POST["pass"])) {
    $message = "ユーザー名とパスワードを入力してください";
  }
  //ユーザー名とパスワードが送信されて来た場合
  else {
    //post送信されてきたユーザー名がデータベースにあるか検索
    $signin_user = $dbh->select_users_name($_POST['name']);
    $signin_user = $signin_user[0];
    var_dump($signin_user);

    //検索したユーザー名に対してパスワードが正しいかを検証
    //if (!password_verify($_POST['pass'], $signin_user['pass'])) {
    //  $message="ユーザー名かパスワードが違います";
    //} else {
    //  session_regenerate_id(TRUE); //セッションidを再発行
    //  $_SESSION["signin"] = $signin_user['id'];    
    //  if ($signin_user['role'] === "1"){
    //    header("Location: index_admin.php"); //ログイン後のページにリダイレクト
    //    exit();
    //  } else {
    //    header("Location: index.php"); //ログイン後のページにリダイレクト
    //    exit();
    //  } 
    //}
    if (password_verify($_POST['pass'], $signin_user['pass']) && $signin_user['status'] === TRUE){
      session_regenerate_id(TRUE); //セッションidを再発行
      $_SESSION["signin"] = $signin_user['id'];
      if ($signin_user['role'] === 1){
        $_SESSION["role"] = $signin_user['role'];
        header("Location: index_admin.php"); //ログイン後のページにリダイレクト
        exit();
      } else {
        header("Location: index.php"); //ログイン後のページにリダイレクト
        exit();
      } 
    }elseif($signin_user['status'] !== TRUE){
      $message="退会済みユーザのため改めて新規登録を行ってください";
    }else{
      $message="ユーザー名かパスワードが違います";
    }
  }
}

$message = htmlspecialchars($message);
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>Sginin page</title>
    <link href="signin.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <h1>Signin page</h1>
    <div class="message"><?php echo $message;?></div>
    <div class="signinform">
      <form action="signin.php" method="post">
        <ul>
        <li>NAME：<input name="name" type="text"></li>
        <li>PASSWORD：<input name="pass" type="password"></li>
        <li><input name="sginin" type="submit" value="SGININ"></li>
        </ul>
      </form>
    </div>
    <!-- guestユーザーにつきhiddenを使用 -->
    <form action="signin.php" method="post">
      <input type="hidden" name="name" value="guest">
      <input type="hidden" name="pass" value="guestpass">
      <input type="submit" name="guest_login" value="GUEST SGININ">
    </form>
    <p>Click<a href="signup.php"> here </a>for new registration</p>
  </body>
</html>