<?php
require_once '../action/db_controller.php';

// DUBUG
echo "<pre>";
var_dump($_POST);
echo "</pre>";

//セッションを使うことを宣言
session_start();

$dbh = new DbController;

//ログイン状態の場合ログイン後のページにリダイレクト
if (isset($_SESSION["signin"])) {
  session_regenerate_id(TRUE);
  header("Location: index.php");
  exit();
}

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
    if (!password_verify($_POST['pass'], $signin_user['pass'])) {
      $message="ユーザー名かパスワードが違います";
    }
    //正しいとき
    else {
      session_regenerate_id(TRUE); //セッションidを再発行
      $_SESSION["signin"] = $signin_user['id'];    
      if ($signin_user['role'] === "1"){
        header("Location: index_admin.php"); //ログイン後のページにリダイレクト
        exit();
      } else {
        header("Location: index.php"); //ログイン後のページにリダイレクト
        exit();
      } 
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
        <li>ユーザー名：<input name="name" type="text"></li>
        <li>パスワード：<input name="pass" type="password"></li>
        <li><input name="送信" type="submit"></li>
        </ul>
      </form>
    </div>
    <!-- guestユーザーにつきhiddenを使用 -->
    <form action="signin.php" method="post">
      <input type="hidden" name="name" value="guest">
      <input type="hidden" name="pass" value="guestpass">
      <input type="submit" name="guest_login" value="ゲストログイン">
    </form>
    <p>新規登録は<a href="signup.php">こちら</a></p>
  </body>
</html>