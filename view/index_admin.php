<h1>Admin index</h1>
<a href="../action/logout_function.php"><button type="button">LOGOUT</button></a>
<?php 
session_start();
var_dump($_SESSION['role']);
?>