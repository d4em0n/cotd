<?php
session_start();
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}
if(!isset($_GET['id'])) {
	header('Location: dashboard.php');
	exit;
}

include "dbconn.php";

$id = $_GET['id'];
$sql = "SELECT * from tasks where task_id='$id'";
$data = mysqli_query($koneksi, $sql);
$res = mysqli_fetch_assoc($data);

$sql = "SELECT * from subtasks where task_id='$id'";
$data = mysqli_query($koneksi, $sql);

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CoTodo: Collaborative Todo List</title>

		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">

		<link rel="stylesheet" href="dashboard.css">
		<link rel="stylesheet" href="detail.css">

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
				<div class="card <?php echo $res['is_finished']==1 ? "is-completed" : ""; ?>">
					<header class="card-header">
						<div class="card-header-title">
							<span class="tag is-light">#<?php echo $res['task_id'];?></span>
							<?php echo $res['name']; ?>
						</div>

						<button type="button" class="button button-status">
							<span class="icon">
							<a class="fa has-text-success <?php echo $res['is_finished']==1 ? "fa-check-square" : "fa-square-o"; ?>"
									href="finishtask.php?id=<?php echo $res['task_id']; ?>&finished=<?php echo $res['is_finished'] ?>">
								</a>
							</span>
						</button>
					</header>
					<div class="card-content">
						<div class="content">
						<p style="white-space:pre"><?php echo $res['description']; ?></p>
							<hr />
							<small><time datetime="2016-1-1"><?php echo date("g:i A - d M Y", strtotime($res['deadline'])); ?></time></small>
						</div>
					</div>
					<footer class="card-footer">
						<div class="card-footer-item">
							<button type="button" class="button is-danger is-outlined">
								Delete
							</button>
						</div>
						<div class="card-footer-item">
							<a href="edittask.php?id=<?php echo $res['task_id']; ?>" class="button is-primary is-outlined">
								Edit
							</a>
						</div>
					</footer>
				</div>

				<section class="section">
					<div class="mb-5">
						<h1 class='title has-text-centered'>
							SubTasks
						</h1>
					</div>
					<div class='columns is-mobile is-centered'>
						<div class='column is-8'>
							<form action="addsubtask.php" method="post">
								<input type="hidden" name="task_id" value="<?php echo $id; ?>">
								<div class="field has-addons mb-2">
									<div class="control has-icons-left has-icons-right is-expanded">
										<input type="text" name="name" class="input is-success" placeholder="Enter new tasks">
										<span class="icon is-left">
											<i class="fa fa-plus"></i>
										</span>
									</div>
									<p class="control">
										<button type="submit" class="button is-success">Add SubTask</submit>
									</p>
								</div>
							</form>
							<?php while ($row=mysqli_fetch_array($data)) {  ?>
							<div class='list'>

								<div class="card <?php echo $row[2]==1 ? "is-completed" : ""; ?>">
									<header class="card-header">
										<div class="card-header-title is-2 has-text-weight-medium">
										<span class="tag is-light">#<?php echo $row[0] ?></span>
										<?php echo $row[1]?>
										</div>

										<button type="button" class="button button-status">
											<span class="icon">
											<a class="fa has-text-success <?php echo $row[2]==1 ? "fa-check-square" : "fa-square-o"; ?>"
													href="finishsubtask.php?id=<?php echo $row[0]; ?>&finished=<?php echo $row[2] ?>">
												</a>
											</span>
										</button>
										<button type="button" class="button button-status">
											<span class="icon">
											<a class="fa has-text-danger fa-trash"
													href="deletesubtask.php?id=<?php echo $row[0]; ?>">
											</a>
											</span>
										</button>
									</header>
								</div>
							</div>
							<?php } ?>

						</div>
					</div>
				</section>
			</section>
		</div>
	</body>
</html>
