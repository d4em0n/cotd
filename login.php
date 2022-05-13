<?php

session_start();
include "dbconn.php";
$username = $_POST['user'];
$password = hash('sha256', $_POST['password']);

$data = mysqli_query($koneksi,"select * from users where username='$username' and password='$password'");

// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);

if($cek > 0){
	$row = $data->fetch_array(MYSQLI_ASSOC);
	$_SESSION['username'] = $username;
	$_SESSION['user_id'] = $row['user_id'];
	$_SESSION['status'] = "login";
	header('Location: dashboard.php');
}else{
	header('Location: index.php');
}
?>
