<?php 

require_once '../action/db_controller.php';

// DUBUG
// echo "<pre>";
// var_dump($_GET);
// echo "</pre>";

session_start();

//サインイン状態ではない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  session_regenerate_id(TRUE);
  header("Location: signin.php");
  exit();
}

$dbh = new DbController;
$res_show = $dbh->select_users_id($_SESSION["signin"]); //GETされたIDよりレコードを取得
$res_show = $res_show[0];
// DEBUG
//var_dump($res_show);
//var_dump($_SESSION["signin"]);

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
                <h1>USER EDIT</h1>
                <form action="../action/index_function.php" method="post" class="row row-cols-lg-auto g-3 justify-content-center align-items-center mb-4 pb-2">
                  <div class="col-12">
                    <div class="form-outline">
                      <input type="text" id="UserName" name=":name" class="form-control" placeholder="<?php echo($res_show['name']); ?>">
                      <label class="form-label" for="UserName"></label>
                      <input type="text" id="UserMail" name=":mail" class="form-control" placeholder="<?php echo($res_show['mail']); ?>">
                      <label class="form-label" for="UserMail"></label>
                      <!-- TODO : ここの記述要リファクタリング -->
                      <input type="hidden" name=":id" value="<?php echo($res_show['id']); ?>">
                      <input type="hidden" name="_method" value="PUT">
                      <!--<input type="submit" name="update_user" value="UPDATE">-->
                    </div>
                  </div>
                  <div class="col-6 text-center">
                    <button type="submit" class="btn btn-primary" name="update_user" value="UPDATE">UPDATE</button>
                  </div>
                  <div class="col-6 text-center">
                    <form action="" method="">
                      <script src="../js/function_script.js"></script>
                      <button type="submit" class="btn btn-danger" onclick="return confirmFunction()" formaction="../action/withdrow_function.php">Withdrow</button>
                    </form>
                  </div>
                </form>
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