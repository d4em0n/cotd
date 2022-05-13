<?php
session_start();
include "dbconn.php";
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}
$task_id = $_POST['task_id'];
$name = $_POST['name'];
$description = $_POST['description'];
$user_id = $_SESSION['user_id'];
$deadline = $_POST['deadline_date'].' '.$_POST['deadline_time'].':00';
$users = $_POST['invited_users'];
$sql = "UPDATE tasks SET";
$sql .= " name='$name',";
$sql .= " description='$description',";
$sql .= " deadline='$deadline'";
$sql .= " where task_id='$task_id'";
var_dump($users);
if(mysqli_query($koneksi, $sql)) {
	$sql = "DELETE FROM users_collab WHERE task_id='$task_id'";
	$res = mysqli_query($koneksi, $sql);
	if($res) {
		if(sizeof($users) > 0) {
			$sql = "INSERT INTO users_collab (user_id, task_id) VALUES ";
			foreach ($users as $k=>$uid) {
				$sql .= "('$uid', '$task_id')";
				if($k !== sizeof($users)-1) $sql .= ', ';
			}
			echo $sql."<br>";
			$res = mysqli_query($koneksi, $sql);
			if($res) {

			} else {
				echo "insert error<br>";
				var_dump($koneksi->error);
				die;
			}
		}
	} else {
		echo "delete error<br>";
		var_dump($koneksi->error);
		die;
	}

	header("Location: dashboard.php");
}
echo $sql;
?>
