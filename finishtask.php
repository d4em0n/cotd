<?php
session_start();
include "dbconn.php";
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}

$task_id = $_GET['id'];
$finished = (int)!$_GET['finished'];

$sql = "UPDATE tasks SET is_finished='$finished' where task_id='$task_id'";
$res = mysqli_query($koneksi, $sql);
if($res) {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	var_dump($res);
}
?>
