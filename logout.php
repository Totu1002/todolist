<?php
//セッションを使うことを宣言
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  header("Location: index.php");
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
<title>ログアウトページ</title>
<link href="login.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>ログアウトページ</h1>
<div class="message">ログアウトしました</div>
<a href="signin.php">ログインページへ</a>
</body>
</html>