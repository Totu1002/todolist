<?php 

require_once '../action/db_controller.php';

session_start();

var_dump($_POST);
if (isset($_GET)){
  var_dump($_GET);
  var_dump($_GET["message"]);
}

//サインイン状態ではない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  session_regenerate_id(TRUE);
  header("Location: signin.php");
  exit();
}

$dbh = new DbController;

$user_select_result = $dbh->select_users_id($_SESSION["signin"]); //GETされたIDよりレコードを取得
$user = $user_select_result[0];

?>

<!DOCTYPE html>
<html>
  <head>
    <title>TEST</title>
    <link rel="stylesheet" href="../css/.css">
    <link rel="stylesheet" href="../bootstrap-4.1.3-dist/css/bootstrap.min.css">
  </head>
  <body>
  <section class="vh-100" style="background-color: #eee;">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col col-lg-9 col-xl-7">
            <div class="card rounded-3">
              <div class="card-body p-4">
                <h1>Change Password</h1>
                <form action="../action/index_function.php" method="post" class="row row-cols-lg-auto g-3 justify-content-center align-items-center mb-4 pb-2">
                  <div class="col-12">
                    <div class="form-outline">
                      <h5>Current Password</h5>
                      <input type="text" id="CurrentPassword" name="current_password" class="form-control">
                      <label class="form-label" for="CurrentPassword"></label>
                      <h5>New Password</h5>
                      <input type="text" id="NewPassword" name="new_password" class="form-control">
                      <label class="form-label" for="NewPassword"></label>
                      <h5>New Password Again</h5>
                      <input type="text" id="NewPasswordAgain" name="again_new_password" class="form-control">
                      <label class="form-label" for="NewPasswordAgain"></label>

                      <!-- TODO : ここの記述要リファクタリング -->
                      <input type="hidden" name=":id" value="<?php echo($user['id']); ?>">
                      <input type="hidden" name="_method" value="PUT">
                      <!--<input type="submit" name="update_user" value="UPDATE">-->
                    </div>
                  </div>
                  <div class="col-4 text-center">
                    <button type="submit" class="btn btn-primary" name="update_password" value="UPDATE">UPDATE</button>
                  </div>
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
    </html>
  </body>