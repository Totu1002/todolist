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
var_dump($res_show);

var_dump($_SESSION["signin"]);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>TEST</title>
  </head>
  <body>
    <h1>USER EDIT</h1>
    <form action="../action/index_function.php" method="post">
      <ul>
        <li><span>Name</span><input type="text" name=":name" value="<?php echo($res_show['name']); ?>"></li>
        <li><span>Mail</span><input type="text" name=":mail" value="<?php echo($res_show['mail']); ?>"></li> 
        <input type="hidden" name=":id" value="<?php echo($res_show['id']); ?>">
        <input type="hidden" name="_method" value="PUT">
        <input type="submit" name="update_user" value="UPDATE">
      </ul>
    </form>
    <form action="" method="">
        <script src="../js/function_script.js"></script>
        <button type="submit" onclick="return confirmFunction()" formaction="../action/withdrow_function.php">Withdrow</button>
    </form>
  </body>
</html>