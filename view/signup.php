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
      <form action="../action/signup_function.php" method="post"> 
        <div>
            <label>NAME：<label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>MAIL：<label>
            <input type="text" name="mail" required>
        </div>
        <div>
            <label>PASSWORD：<label>
            <input type="password" name="pass" required>
        </div>
        <input type="submit" value="SIGNUP">
      </form>
    </div>
    <p>Click<a href="signin.php"> here </a>if you have already registered</p>
  </body>
</html>