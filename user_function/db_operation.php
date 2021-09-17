<?php
require_once 'db_function.php';

class DbOperation{
	
}

$dbh = new DbFunction;
$dbh->db_create_table();
$select_items = $dbh->db_select();
var_dump($select_items);
?>