<?php
require_once 'db_function.php';

$name = $_POST['name'];
$mail = $_POST['mail'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

//class
$pdo = new DbFunction;
$dbh = $pdo->db_conection();

//$dbh = new PDO('sqlite:Login.sqlite3');
//$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$sql = "SELECT * FROM users WHERE mail = :mail";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':mail', $mail);
$stmt->execute();
$member = $stmt->fetch();
if ($member['mail'] === $mail) {
    $msg = '同じメールアドレスが存在します。';
    $link = '<a href="signup.php">戻る</a>';
} else {
    $sql = "INSERT INTO users(name, mail, pass) VALUES (:name, :mail, :pass)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':mail', $mail);
    $stmt->bindValue(':pass', $pass);
    $stmt->execute();
    $msg = '会員登録が完了しました';
    $link = '<a href="login.php">ログインページ</a>';
}
echo "<h1><?php echo $msg; ?></h1>";
echo "$link"; 
?>