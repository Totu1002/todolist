<?php

require_once '../action/db_controller.php';

// DUBUG
echo "<pre>";
var_dump($_POST);
echo "</pre>";

session_start();

//サインイン状態ではない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  session_regenerate_id(TRUE);
  header("Location: signin.php");
  exit();
}

//サインイン時表示用メッセージ
$message = $_SESSION['signin']."さんようこそ";
$message = htmlspecialchars($message);

$dbh = new DbController();

?>
<!DOCTYPE html>
<html>
  <head>
    <title>TEST</title>
  </head>
  <body>
    <div class="message"><?php echo $message;?></div>
    <a href="../action/logout_function.php">ログアウト</a>
    <h1>FORM</h1>
    <form action="../action/index_function.php" method="post">
      <ul>
        <li><span>Title</span><input type="text" name=":title"></li>
        <li><span>Body</span><input type="text" name=":body"></li>
        <!-- タスクステータス管理用value デフォルト:1 未完了 -->
        <input type="hidden" name=":done" value="1">
        <!-- user_idへusers/idを送信 -->
        <input type="hidden" name=":user_id" value="<?php echo($_SESSION['signin']) ?>">        
        <li><input type="submit" name="insert" value="SUBMIT"></li>
      </ul>
    </form>
    <h1>LIST</h1>
    <ul>
      <?php
        $res_select = $dbh->select_user_task($_SESSION['signin']);
        // DEBUG
        //var_dump($res_select);
        foreach($res_select as $row){
      ?>
      <table>
        <tr>
          <td><?php echo "{$row['id']}" ?></td>
          <td><?php echo "{$row['user_id']}" ?></td>
          <td><?php echo "{$row['title']}"; ?></td>
          <td><?php echo "{$row['body']}"; ?></td>
          <td><?php echo "{$row['done']}"; ?></td>
          <td>
            <?php  ?>
            <form action="./edit.php" method="get">
              <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
              <input type="submit" name="edit" value="EDIT">
            </form>
          </td>
          <td>
            <?php  ?>
            <form action="../action/index_function.php" method="post">
              <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
              <input type="hidden" name="_method" value="DELETE"> 
              <input type="submit" name="delete" value="DELETE">
            </form>
          </td>
        </tr>
      </table>
      <?php } ?>
    </ul>
  </body>
</html>