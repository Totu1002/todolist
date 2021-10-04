<?php
require_once './db_controller.php';

session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION['signin'])) {
  header("Location: index.php");
  exit();
}

//TODO 退会処理→正常性判断処理を追加したい
$dbh = new DbController();
$dbh->update_users_status("FALUSE",$_SESSION['signin']);

//セッション変数をクリア
$_SESSION = array();

//クッキーに登録されているセッションidの情報を削除
if (ini_get("session.use_cookies")) {
  setcookie(session_name(), '', time() - 42000, '/');
}

//セッションを破棄
session_destroy();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>TEST</title>
<link href="login.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>Withdrow</h1>
<div class="message">Withdrawal is complete</div>
<a href="../view/signup.php">SIGNUP</a>
</body>
</html>