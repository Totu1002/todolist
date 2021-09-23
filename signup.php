<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset='utf-8'>
    <title>Signup page</title>
  </head>
  <body>
    <meta charset="UTF-8">
    <h1>Signup page</h1>
    <div class="signupform">
      <form action="register.php" method="post"> 
        <div>
            <label>名前：<label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>メールアドレス：<label>
            <input type="text" name="mail" required>
        </div>
        <div>
            <label>パスワード：<label>
            <input type="password" name="pass" required>
        </div>
        <input type="submit" value="新規登録">
      </form>
    </div>
    <p>すでに登録済みの方は<a href="signin.php">こちら</a></p>
  </body>
</html>