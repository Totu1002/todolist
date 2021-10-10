<?php
require_once '../action/db_controller.php';

// DUBUG
//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";

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
    <link rel="stylesheet" href="../css/singin_style.css">
    <link rel="stylesheet" href="../bootstrap-4.1.3-dist/css/bootstrap.min.css">
  </head>
  <body class="text-center">
    <div class="message"><?php echo $message;?></div>

    <a id="skippy" class="sr-only sr-only-focusable" href="#content">
        <div class="container">
          <span class="skiplink-text">Skip to main content</span>
        </div>
    </a>
    <div class="form-signin">
      <form action="signin.php" method="post" class="">
        <!--<img class="mb-4" src="../../assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">-->
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

        <label for="inputName" class="sr-only">User Name</label>
        <input type="name" id="inputName" class="form-control" name="name" placeholder="UserName" required="" autofocus="">

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="pass" id="inputPassword" class="form-control" name="pass" placeholder="Password" required="">
        
        <div class="checkbox mb-3">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block mb-3" name="sginin" type="submit" value="SGININ">SGININ</button>
      </form>

      <!-- guestユーザーにつきhiddenを使用 -->
      <form action="signin.php" method="post" class="">
        <input type="hidden" name="name" value="guest">
        <input type="hidden" name="pass" value="guestpass">
        <input type="submit" class="btn btn-lg btn-warning btn-block mb-3" name="guest_login" value="GUEST SGININ">
      </form>
      <p>Click<a href="signup.php"> here </a>for new registration</p>
    </div>
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
    <script src="./jquery/jquery-3.6.0.slim.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>-->
    <script src="./jquery/node_modules/popper.js/dist/popper.min.js"></script>
    <!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->
    <script src="./bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>