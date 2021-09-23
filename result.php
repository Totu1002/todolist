<?php
//セッションを使うことを宣言
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  header("Location: index.php");
  exit();
}

//ログインされている場合は表示用メッセージを編集
$message = $_SESSION['signin']."さんようこそ";
$message = htmlspecialchars($message);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログイン成功ページ</title>
<link href="login.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>ログイン成功ページ</h1>
<div class="message"><?php echo $message;?></div>
<a href="logout.php">ログアウト</a>
</body>
</html>