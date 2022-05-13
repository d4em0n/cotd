<?php
session_start();
include "dbconn.php";
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}

$subtask_id = $_GET['id'];
$finished = (int)!$_GET['finished'];

$sql = "UPDATE subtasks SET is_finished='$finished' where id_subtask='$subtask_id'";
$res = mysqli_query($koneksi, $sql);
if($res) {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	var_dump($res);
}
?>
