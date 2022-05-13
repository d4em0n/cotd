<?php
session_start();
include 'dbconn.php';
if(!isset($_SESSION['user_id'])) {
	header('Location: index.php');
	exit;
}

if(!isset($_GET['id'])) {
	header('Location: dashboard.php');
	exit;
}

$task_id = $_GET['id'];
$sql = "";
$sql .= "select tasks.task_id, tasks.name, tasks.description, tasks.deadline, group_concat(users.user_id) as invited_users, tasks.user_id";
$sql .= " from tasks";
$sql .= " left join users_collab on tasks.task_id=users_collab.task_id";
$sql .= " left join users on users.user_id=users_collab.user_id";
$sql .= " where tasks.task_id='$task_id'";
$data = mysqli_query($koneksi, $sql);
$res = mysqli_fetch_assoc($data);

$deadline = strtotime($res['deadline']);
$deadline_time = date("H:i", $deadline);
$deadline_date = date("Y-m-d", $deadline);

$user_id = $res['user_id'];

$sql = "select user_id, fullname from users where not user_id='$user_id'";
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
				<form action="updatetask.php" method="post" class="box">
				<input type="hidden" value="<?php echo $res['task_id']; ?>" name="task_id">
				<div class="columns is-vcentered is-centered">
					<div class="column is-2-desktop has-text-right">
						<p>Task name :</p>
					</div>
					<div class="column is-6-desktop">
						<div class="field">
							<p class="control">
							<input class="input" name="name" type="text" placeholder="Nama task" value="<?php echo $res['name'] ?>">
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
							<textarea class="textarea" name="description" placeholder="Your description here.."><?php echo $res['description']; ?></textarea>
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
								<input type="date" name="deadline_date" value="<?php echo $deadline_date; ?>">
								<input type="time" name="deadline_time" value="<?php echo $deadline_time; ?>">
							</div>
						</div>
					</div>
				</div>

				<div class="columns is-vcentered is-centered">
					<div class="column is-8-desktop has-text-centered">
						<button type="submit" class="button is-medium is-fullwidth is-info">Update Task</button>
					</div>
				</div>
			</form>
			</section>
		</div>
	</body>
<script type="text/javascript">
var $select =	$('#select-user').selectize();
var selectize = $select[0].selectize;
var selected_ids = [<?php echo $res['invited_users']; ?>];
selectize.setValue(selected_ids);
</script>
</html>
