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
$res_show = $dbh->select_tasks_id($_GET[':id'],$_SESSION["signin"]); //GETされたIDよりレコードを取得
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
    <h1>TASK EDIT</h1>
    <form action="../action/index_function.php" method="post">
      <ul>
        <li><span>Title</span><input type="text" name=":title" value="<?php echo($res_show['title']); ?>"></li>
        <li><span>Body</span><input type="text" name=":body" value="<?php echo($res_show['body']); ?>"></li> 
        <input type="hidden" name=":id" value="<?php echo($res_show['id']); ?>">
        <input type="hidden" name="_method" value="PUT">
        <li><input type="submit" name="update_task" value="UPDATE"></li>
      </ul>
    </form>
  </body>
</html>