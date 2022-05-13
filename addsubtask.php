<?php
session_start();
include "dbconn.php";
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}
$name = $_POST['name'];
$task_id = $_POST['task_id'];
$sql = "INSERT INTO subtasks (name, is_finished, task_id) VALUES ('$name', '0', '$task_id')";
$res=mysqli_query($koneksi, $sql);
if($res) {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
echo $sql;
?>
