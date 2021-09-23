<?php
require_once 'db_controller.php';

$name = $_POST['name'];
$mail = $_POST['mail'];
//passwordハッシュ化処理
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

//class
$pdo = new DbController;
$dbh = $pdo->db_conect();

//$dbh = new PDO('sqlite:Login.sqlite3');
//$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


//TODO ユーザー名も同一は拒否するようにしたい→signin処理でuser判別に影響ある
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
    $link = '<a href="signin.php">ログインページ</a>';
}
echo "<h1><?php echo $msg; ?></h1>";
echo "$link"; 
?>