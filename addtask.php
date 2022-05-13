<?php
session_start();
include "dbconn.php";
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}
$name = $_POST['name'];
$description = $_POST['description'];
$user_id = $_SESSION['user_id'];
$deadline = $_POST['deadline_date'].' '.$_POST['deadline_time'].':00';
$users = $_POST['invited_users'];
$sql = "INSERT INTO tasks (name, description, deadline, user_id, is_finished) VALUES ('$name', '$description', '$deadline', '$user_id', '0')";
echo $sql."<br>";

if(mysqli_query($koneksi, $sql)) {
	if(sizeof($users) > 0) {
		$last_id = mysqli_insert_id($koneksi);
		$sql = "INSERT INTO users_collab (user_id, task_id) VALUES ";
		foreach ($users as $k=>$uid) {
			$sql .= "('$uid', '$last_id')";
			if($k !== sizeof($users)-1) $sql .= ', ';
		}
		echo $sql."<br>";
		$res = mysqli_query($koneksi, $sql);
	}
}

header("Location: dashboard.php");

?>
