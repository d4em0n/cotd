<?php
$koneksi = new mysqli("localhost","root","","cotodo");

// Check connection
if ($koneksi->connect_errno) {
	echo "Failed to connect to MySQL: " . $koneksi->connect_error;
	exit();
}
?>
