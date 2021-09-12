<?php 

require_once 'db_function.php';

// echo "<pre>";
// var_dump($_GET);
// echo "</pre>";

$dbh = new DbConection;

$res_show = $dbh->db_select_show($_GET[':id']);
$res_show = $res_show[0];
// DEBUG
// var_dump($res_show);

//var_dump($res_show['title']);
//var_dump($res_show['body']);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>TEST</title>
  </head>
  <body>
    <h1>EDIT</h1>
    <form action="index.php" method="post"> <!-- 初期値の場合placeholder -->
      <ul>
        <li><span>Title</span><input type="text" name=":title" value="<?php echo($res_show['title']); ?>"></li>
        <li><span>Body</span><input type="text" name=":body" value="<?php echo($res_show['body']); ?>"></li> 
        <input type="hidden" name=":id" value="<?php echo($res_show['id']); ?>">
        <input type="hidden" name=":done" value="1">
        <input type="hidden" name="_method" value="PUT">
        <li><input type="submit" name="update" value="UPDATE"></li>
      </ul>
    </form>
  </body>
</html>