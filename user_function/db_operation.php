<?php
require_once 'db_function.php';

//table作成、確認用クラス
class DbOperation{
	
}

$dbh = new DbFunction;
$dbh->db_create_users_table();
$dbh->db_create_tasks_table();
$select_items = $dbh->db_select();
var_dump($select_items);
?>