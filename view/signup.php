<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset='utf-8'>
    <title>Signup page</title>
    <link rel="stylesheet" href="../css/singin_style.css">
    <link rel="stylesheet" href="../bootstrap-4.1.3-dist/css/bootstrap.min.css">
  </head>
  <body class="text-center">
    <meta charset="UTF-8">
    <div class="form-signin">
      <form action="../action/signup_function.php" method="post" class="">
        <h1 class="h3 mb-3 font-weight-normal">Please sign up</h1>

        <label for="inputName" class="sr-only">User Name</label>
        <input type="name" id="inputName" class="form-control mb-2" name="name" placeholder="UserName" required="" autofocus="">

        <label for="inputMail" class="sr-only">Mailaddress</label>
        <input type="mail" id="inputMail" class="form-control mb-2" name="mail" placeholder="MailAddress" required="" autofocus="">

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="pass" id="inputPassword" class="form-control mb-2" name="pass" placeholder="Password" required="">
        <button class="btn btn-lg btn-primary btn-block mb-3" name="sginin" type="submit" value="SIGNUP">SGINUP</button>
      </form>
      <p>Click<a href="signin.php"> here </a>if you have already registered</p>
    </div>
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
    <script src="./jquery/jquery-3.6.0.slim.min.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>-->
    <script src="./jquery/node_modules/popper.js/dist/popper.min.js"></script>
    <!--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>-->
    <script src="./bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
  </body>
</html>