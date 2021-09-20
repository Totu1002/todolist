<?php
require_once 'db_function.php';

//table作成、確認用クラス
class DbOperation{
	
}

$dbh = new DbFunction;
//$dbh->db_conection();

//新規テーブル作成用処理
//$dbh->db_create_users_table();
//$dbh->db_create_tasks_table();

//$select_users = $dbh->db_select_users();
//var_dump($select_users);
//$select_tasks = $dbh->db_select_tasks();
//var_dump($select_tasks);

//TODO
//テスト用デフォルトユーザー作成処理
//$dbh->db_insert_default_user();

$select_users = 'SELECT * FROM users';
$users_items = $dbh->db_select($select_users);
var_dump($users_items);

$select_tasks = 'SELECT * FROM tasks';
$tasks_items = $dbh->db_select($select_tasks);
var_dump($tasks_items);
?>