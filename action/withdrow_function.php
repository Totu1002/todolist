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
$dbh->update_users_status("FALSE",$_SESSION['signin']);

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
<section class="vh-100" style="background-color: #eee;">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col col-lg-9 col-xl-7">
            <div class="card rounded-3">
              <div class="card-body p-4">
                <h1>Withdrow</h1>
                <p class="message">Withdrawal is complete</p>
                <a href="../view/signup.php">SIGNUP</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
    <script src="./jquery/jquery-3.6.0.slim.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>-->
    <script src="./jquery/node_modules/popper.js/dist/popper.min.js"></script>
    <!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->
    <script src="./bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
</body>
</html>