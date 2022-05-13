<?php
session_start();
include "dbconn.php";
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}

$task_id = $_GET['id'];

$sql = "DELETE FROM users_collab where task_id='$task_id'";
$res = mysqli_query($koneksi, $sql);
if($res) {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	echo $sql;
}

$sql = "DELETE FROM subtasks where task_id='$task_id'";
$res = mysqli_query($koneksi, $sql);
if($res) {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	echo $sql;
}

$sql = "DELETE FROM tasks where task_id='$task_id'";
$res = mysqli_query($koneksi, $sql);
if($res) {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
	echo $sql;
}
?>
