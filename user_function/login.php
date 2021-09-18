<?php
require_once 'db_function.php';

//セッションを使うことを宣言
session_start();

//データベースに接続する
try {
  $pdo = new DbFunction;
  $dbh = $pdo->db_conection();
}
catch (PDOExeption $e) {
  exit ('データベースエラー');
}

//ログイン状態の場合ログイン後のページにリダイレクト
if (isset($_SESSION["login"])) {
  session_regenerate_id(TRUE);
  header("Location: success.php");
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
    try {
      $stmt = $dbh->prepare('SELECT * FROM users WHERE name=?');
      $stmt -> bindParam(1, $_POST['name'], PDO::PARAM_STR, 10);
      $stmt -> execute();
      $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOExeption $e) {
      exit('データベースエラー');
    }

    //検索したユーザー名に対してパスワードが正しいかを検証
    //正しくないとき
    if (!password_verify($_POST['pass'], $result['pass'])) {
      $message="ユーザー名かパスワードが違います";
    }
    //正しいとき
    else {
      session_regenerate_id(TRUE); //セッションidを再発行
      $_SESSION["login"] = $_POST['name']; //セッションにログイン情報を登録
      header("Location: success.php"); //ログイン後のページにリダイレクト
      exit();
    }
  }
}

$message = htmlspecialchars($message);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>ログインページ</title>
<link href="login.css" rel="stylesheet" type="text/css">
</head>
<body>
<h1>ログインページ</h1>
<div class="message"><?php echo $message;?></div>
<div class="loginform">
  <form action="login.php" method="post">
    <ul>
    <li>ユーザー名：<input name="name" type="text"></li>
    <li>パスワード：<input name="pass" type="password"></li>
    <li><input name="送信" type="submit"></li>
    </ul>
  </form>
</div>
</body>
</html>