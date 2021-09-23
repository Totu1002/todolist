<?php
require_once 'db_controller.php';

// DUBUG
echo "<pre>";
var_dump($_POST);
echo "</pre>";

//セッションを使うことを宣言
session_start();

//データベースに接続する
try {
  $pdo = new DbController;
  $dbh = $pdo->db_conect();
}
catch (PDOExeption $e) {
  exit ('データベースエラー');
}

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
    try {
      $stmt = $dbh->prepare('SELECT * FROM users WHERE name=?');
      $stmt -> bindParam(1, $_POST['name'], PDO::PARAM_STR, 10);
      $stmt -> execute();
      $signin_user = $stmt -> fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOExeption $e) {
      exit('データベースエラー');
    }

    //検索したユーザー名に対してパスワードが正しいかを検証
    //正しくないとき:パスワードをハッシュで保存、検証する場合
    if (!password_verify($_POST['pass'], $signin_user['pass'])) {
    //if ($_POST['pass'] !== $result['pass']) {
      // DEBUG
      echo("ユーザーパスワード検証");
      $message="ユーザー名かパスワードが違います";
    }
    //正しいとき
    else {
      session_regenerate_id(TRUE); //セッションidを再発行
      //$_SESSION["signin"] = $_POST['name']; //セッションにログイン情報を登録
      $_SESSION["signin"] = $signin_user['id'];
      header("Location: result.php"); //ログイン後のページにリダイレクト
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
  </body>
</html>