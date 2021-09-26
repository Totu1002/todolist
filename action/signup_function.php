<?php
require_once 'db_controller.php';

$name = $_POST['name'];
$mail = $_POST['mail'];
//passwordハッシュ化処理
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

$dbh = new DbController;
$member_mail = $dbh->select_users_mail($mail);
$member_mail = $member_mail[0];

$member_name = $dbh->select_users_name($name);
$member_name = $member_name[0];

if ($member_name['name'] === $name) {
    $msg = 'The same username already exists.';
    $link = '<a href="../view/signup.php">return</a>';
} elseif($member_mail['mail'] === $mail) {
    $msg = 'The same email address exists.';
    $link = '<a href="../view/signup.php">return</a>';
} else {
    $dbh->insert_users($db_val);
    $msg = 'Member registration is complete';
    $link = '<a href="../view/signin.php">signin page</a>';
}

?>
<h1><?php echo $msg; ?></h1>
<p><?php echo $link; ?></p>