<?php
require_once 'db_function.php';

session_start();
$mail = $_POST['mail'];
//$dsn = "mysql:host=localhost; dbname=xxx; charset=utf8";
//$username = "xxx";
//$password = "xxx";
//try {
//    $dbh = new PDO($dsn, $username, $password);
//} catch (PDOException $e) {
//    $msg = $e->getMessage();
//}

$pdo = new DbFunction;
$dbh = $pdo->db_conection();

$sql = "SELECT * FROM users WHERE mail = :mail";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':mail', $mail);
$stmt->execute();
$member = $stmt->fetch();
//指定したハッシュがパスワードにマッチしているかチェック
if (password_verify($_POST['pass'], $member['pass'])) {
    //DBのユーザー情報をセッションに保存
    $_SESSION['id'] = $member['id'];
    $_SESSION['name'] = $member['name'];
    $msg = 'ログインしました。';
    $link = '<a href="index.php">ホーム</a>';
} else {
    $msg = 'メールアドレスもしくはパスワードが間違っています。';
    $link = '<a href="login.php">戻る</a>';
}
echo "<h1><?php echo $msg; ?></h1>";
echo "<?php echo $link; ?>";
?>
