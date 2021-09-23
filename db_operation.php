<?php
require_once 'db_function.php';

/**
 * DB動作確認/操作用スクリプト
 * 検証/動作確認用につき、最終的には削除予定
 */

$dbh = new DbFunction;
//$dbh->db_conection();

//新規テーブル作成用処理
//$dbh->create_users_table();
//$dbh->create_tasks_table();

//$select_users = $dbh->db_select_users();
//var_dump($select_users);
//$select_tasks = $dbh->db_select_tasks();
//var_dump($select_tasks);

//テスト用デフォルトユーザー作成処理
//$dbh->insert_default_user();

//$select_users = 'SELECT * FROM users';
//$users_items = $dbh->select_all($select_users);
//var_dump($users_items);

//$select_tasks = 'SELECT * FROM tasks';
//$tasks_items = $dbh->select_all($select_tasks);
//var_dump($tasks_items);

$db_value = "user1";
$user_id = $dbh->select_signin_user($db_value);
var_dump($user_id);
?>