<?php
//セッションを使うことを宣言
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  header("Location: ../view/signin.php");
  exit();
}

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
<h1>LOGOUT</h1>
<div class="message">Logout is complete</div>
<a href="../view/signin.php">SIGNIN</a>
</body>
</html>