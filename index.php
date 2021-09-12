<?php

require_once 'db_function.php';

// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
$dbh = new DbConection();

if(isset($_POST['insert'])){
  $dbh->db_insert($_POST);
}

if(isset($_POST['update'])){
  $dbh->db_update($_POST);
}

if(isset($_POST['delete'])){
  $dbh->db_delete($_POST);
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>TEST</title>
  </head>
  <body>
    <h1>FORM</h1>
    <form action="index.php" method="post">
      <ul>
        <li><span>Title</span><input type="text" name=":title"></li>
        <li><span>Body</span><input type="text" name=":body"></li>
        <input type="hidden" name=":done" value="1">
        <li><input type="submit" name="insert" value="SUBMIT"></li>
      </ul>
    </form>
    <h1>LIST</h1>
    <ul>
      <?php
        $res_select = $dbh->db_select_all();
        // DEBUG
        // var_dump($res_select);
        foreach($res_select as $row){
      ?>
      <table>
        <tr>
          <td><?php echo "{$row['id']}" ?></td>
          <td><?php echo "{$row['title']}"; ?></td>
          <td><?php echo "{$row['body']}"; ?></td>
          <td><?php echo "{$row['done']}"; ?></td>
          <td>
            <?php  ?>
            <form action="edit.php" method="get">
            <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
            <input type="submit" name="edit" value="EDIT">
            </form>
          </td>
          <td>
            <?php  ?>
            <form action="index.php" method="post">
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