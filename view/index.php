<?php

require_once '../action/db_controller.php';

// DUBUG
//echo "<pre>";
//var_dump($_POST);
//echo "</pre>";

session_start();

//サインイン状態ではない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["signin"])) {
  session_regenerate_id(TRUE);
  header("Location: signin.php");
  exit();
}

if (isset($_SESSION["signin"]) && $_SESSION["role"] === 1) {
  session_regenerate_id(TRUE);
  header("Location: index_admin.php");
  exit();
}

//サインイン時表示用メッセージ
$message = "Welcom to user : " . $_SESSION['signin'];
$message = htmlspecialchars($message);

$dbh = new DbController();

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset='utf-8'>
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
    
              <h1>FORM</h1>
              <form action="../action/index_function.php" method="post" class="row row-cols-lg-auto g-3 justify-content-center align-items-center mb-4 pb-2">
                <div class="col-12">
                  <div class="form-outline">
                    <input type="text" id="TaskTitle" name=":title" class="form-control" placeholder="Enter a task title here">
                    <label class="form-label" for="TaskTitle"></label>
                    <input type="text" id="TaskBody" name=":body" class="form-control" placeholder="Enter a task body here">
                    <label class="form-label" for="TaskBody"></label>
                    <!-- タスクステータス管理用value デフォルト:1 未完了 -->
                    <!--<input type="hidden" name=":done" value="1">-->
                    <!-- user_idへusers/idを送信 -->
                    <input type="hidden" name=":user_id" value="<?php echo($_SESSION['signin']) ?>">
                  </div>
                </div>
                <div class="col-12" style="text-align: center;">
                  <button type="submit" class="btn btn-primary"name="insert" value="SUBMIT">SUBMIT</button>
                </div>
              </form>
    
                <h1>ACTIVE LIST</h1>
                <table class="table mb-4">
                  <thead>
                    <tr>
                      <th scope="col">ID.</th>
                      <th scope="col">USER ID</th>
                      <th scope="col">TITLE</th>
                      <th scope="col">BODY</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
              <?php
              $active_tasks = $dbh->select_user_task($_SESSION['signin'],'TRUE');
              // DEBUG
              //var_dump($res_select);
              foreach($active_tasks as $row){
              ?>
                  <tbody>
                    <tr>
                      <td><?php echo "{$row['id']}" ?></td>
                      <td><?php echo "{$row['user_id']}" ?></td>
                      <td><?php echo "{$row['title']}"; ?></td>
                      <td><?php echo "{$row['body']}"; ?></td>
                      <td>
                        <?php  ?>
                        <form action="./edit.php" method="get">
                          <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
                          <input type="submit" formaction="./task_edit.php" name="edit" value="EDIT" class="btn btn-success">
                        </form>
                      </td>
                      <td>
                        <?php  ?>
                        <form action="../action/index_function.php" method="post">
                          <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
                          <input type="hidden" name=":done" value="FALSE">
                          <input type="hidden" name="_method" value="PUT">
                          <input type="submit" name="done" value="DONE" class="btn btn-primary ms-1">
                        </form>
                      </td>
                    </tr>
                  </tbody>
                <?php } ?>
                </table>
              
              
              <h1>COMPLETE LIST</h1>
                <table class="table mb-4">
                  <thead>
                    <tr>
                      <th scope="col">ID.</th>
                      <th scope="col">USER ID</th>
                      <th scope="col">TITLE</th>
                      <th scope="col">BODY</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <?php
                    $complete_tasks = $dbh->select_user_task($_SESSION['signin'],'FALSE');
                    // DEBUG
                    //var_dump($res_select);
                    foreach($complete_tasks as $row){
                  ?>
                  <tbody>
                    <tr>
                      <td><?php echo "{$row['id']}" ?></td>
                      <td><?php echo "{$row['user_id']}" ?></td>
                      <td><?php echo "{$row['title']}"; ?></td>
                      <td><?php echo "{$row['body']}"; ?></td>
                      <td>
                        <form action="../action/index_function.php" method="post">
                          <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
                          <input type="hidden" name="_method" value="DELETE"> 
                          <input type="submit" name="delete" value="DELETE" class="btn btn-danger">
                        </form>
                      </td>
                      <td>
                        <form action="../action/index_function.php" method="post">
                          <input type="hidden" name=":id" value="<?php echo($row['id']); ?>">
                          <input type="hidden" name=":done" value="TRUE">
                          <input type="hidden" name="_method" value="PUT">
                          <input type="submit" name="done" value="RETURN" class="btn btn-success ms-1">
                        </form>
                      </td>
                    </tr>
                  </tbody>
                  <?php } ?>
                </table>
              
            
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