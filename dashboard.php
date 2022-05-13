<?php
session_start();
include 'dbconn.php';
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}
$user_id = $_SESSION['user_id'];
$search = false;
if(isset($_GET['search'])) {
	$search = true;
	$search_sql = "and lower(CONCAT_WS('', name, description)) LIKE lower('%". $_GET['search'] . "%')";
}

$sql = "select task_id, name, description, deadline, is_finished, user_id from tasks where user_id='$_SESSION[user_id]'";
if($search) {
	$sql .= " ".$search_sql;
}
$sql .= "union select tasks.task_id, tasks.name, tasks.description, tasks.deadline, tasks.is_finished, tasks.user_id from users_collab inner join tasks on users_collab.task_id=tasks.task_id where users_collab.user_id='$_SESSION[user_id]'";
if($search) {
	$sql .= " ".$search_sql;
}
$data = mysqli_query($koneksi, $sql);
if(!$data) {
	echo $sql."<br>";
	var_dump($data);
	die;
}

$sql = "select user_id, fullname from users where not user_id='$_SESSION[user_id]'";
$usernames = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CoTodo: Collaborative Todo List</title>

		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.min.css" crossorigin="anonymous" />
		<link rel="stylesheet" href="dashboard.css">

	</head>
	<body>
		<div class="container">
			<nav class="navbar">
				<div class="navbar-brand">
					<a class="navbar-item" href="/dashboard.php">
						<img src="http://bulma.io/images/bulma-logo.png" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
					</a>
				</div>

				<div id="navMenubd-example" class="navbar-menu">
					<div class="navbar-start">
					</div>

					<div class="navbar-end">
						<div class="navbar-item">
							<div class="field is-grouped">
								<p class="control">
									<a class="button is-primary" href="logout.php">
										<span class="icon">
											<i class="fa fa-sign-out"></i>
										</span>
										<span>Logout</span>
									</a>
								</p>
							</div>
						</div>
					</div>
				</div>
			</nav>
			<section class="hero is-primary">
				<div class="hero-body">
					<div class="container">
						<h1 class="title">
							CoTodo
						</h1>
						<h2 class="subtitle">
							This is a project to manage your tasks.
						</h2>
					</div>
				</div>
			</section>

			<section class="section">
				<form action="addtask.php" method="post" class="box">
				<div class="columns is-vcentered is-centered">
					<div class="column is-2-desktop has-text-right">
						<p>Task name :</p>
					</div>
					<div class="column is-6-desktop">
						<div class="field">
							<p class="control">
								<input class="input" name="name" type="text" placeholder="Nama task">
							</p>
						</div>
					</div>
				</div>
				<div class="columns is-vcentered is-centered">
					<div class="column is-2-desktop has-text-right">
						<p>Description :</p>
					</div>
					<div class="column is-6-desktop">

						<div class="field">
							<div class="control">
								<textarea class="textarea" name="description" placeholder="Your description here.."></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="columns is-vcentered is-centered">
					<div class="column is-2-desktop has-text-right">
						<p>Invited users :</p>
					</div>
					<div class="column is-6-desktop">
						<div class="field">
							<p class="control">
									<select name="invited_users[]" class="selectpicker" id="select-user" multiple>
										<option value="">Select user..</option>

										<?php while($row = $usernames->fetch_row()) { ?>
										<option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
										<?php } ?>
									</select>
								</p>
						</div>
					</div>
				</div>
				<div class="columns is-vcentered is-centered">
					<div class="column is-2-desktop has-text-right">
						<p>Deadline :</p>
					</div>
					<div class="column is-6-desktop">
						<div class="field">
							<div class="control">
								<input type="date" name="deadline_date">
								<input type="time" name="deadline_time">
							</div>
						</div>
					</div>
				</div>

				<div class="columns is-vcentered is-centered">
					<div class="column is-8-desktop has-text-centered">
						<button type="submit" class="button is-medium is-fullwidth is-success">Create New Task</button>
					</div>
				</div>
			</form>
			</section>
			<section class="section">
				<div class="columns is-centered">
						<div class='column is-8'>
							<form method="get" action="dashboard.php">
								<div class="field has-addons mb-2">
									<div class="control has-icons-left has-icons-right is-expanded">
										<input type="text" name="search" class="input is-link" placeholder="Search tasks">
										<span class="icon is-left">
											<i class="fa fa-search"></i>
										</span>
									</div>
									<p class="control">
										<button type="submit" class="button is-link">Search</submit>
									</p>
								</div>
							</form>
						</div>
					</div>
			</section>
			<section class="section pt-1">

<?php
while($row = $data->fetch_row()) {

?>


				<div class="card <?php echo $row[4]==1 ? "is-completed" : ""; ?>">
					<header class="card-header">
						<div class="card-header-title">
							<span class="tag is-light">#<?php echo $row[0];?></span>
							<a class="has-text-black" href="detail.php?id=<?php echo $row[0]; ?>"><?php echo $row[1]; ?></a>
						</div>

						<button type="button" class="button button-status">
							<span class="icon">
							<a class="fa has-text-success <?php echo $row[4]==1 ? "fa-check-square" : "fa-square-o"; ?>"
									href="finishtask.php?id=<?php echo $row[0]; ?>&finished=<?php echo $row[4] ?>">
								</a>
							</span>
						</button>
					</header>
					<div class="card-content">
						<div class="content">
						<p style="white-space:pre"><?php echo $row[2]; ?></p>
							<hr />
							<small><time datetime="2016-1-1"><?php echo date("g:i A - d M Y", strtotime($row[3])); ?></time></small>
						</div>
					</div>
					<footer class="card-footer">
						<div class="card-footer-item">
						<a class="button is-danger is-outlined" <?php echo $user_id!=$row[5] ? "disabled" : " href=\"deletetask.php?id=".$row[0]."\"" ?> onclick="return confirm('Apakah kamu ingin menghapus tasks ini?')">
								Delete
							</a>
						</div>
						<div class="card-footer-item">
							<a href="edittask.php?id=<?php echo $row[0]; ?>" class="button is-primary is-outlined">
								Edit
							</a>
						</div>
					</footer>
				</div>


<?php

}
?>

			</section>
		</div>
	</body>
<script type="text/javascript">
$(function() {
	$('#select-user').selectize();
});
</script>
</html>
