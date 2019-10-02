<?php include 'database.php'; ?>
<?php session_start(); ?>
<?php
    $n=1;
	$number = $n;

	$query = "SELECT * FROM questions";
	$results = $conn->query($query) or die ($conn->error.__LINE__);
	$total = $results->num_rows;

	$query = "SELECT * FROM questions WHERE question_number = $number";
	$result = $conn->query($query) or die ($conn->error.__LINE__);
	$question = $result->fetch_assoc();

	$query = "SELECT * FROM choices WHERE question_number = $number";
	$choices = $conn->query($query) or die ($conn->error.__LINE__);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Quiz</title>
	<link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
	<style type="text/css">
		.logout{
			position: absolute;
			left: 1300px;
			top: 20px;
			color: white;
	font-size: 2em;
		}
	</style>
	<header>
		<div class="container">
			<h1>Welcome to Quiz</h1>
		</div>
	</header>
	<main>
		<div class="container">
			<div class="current">Question <?php echo $question['question_number'] ?> of <?php echo $total; ?> </div>
			<p class="questions">
				<?php echo $question['text']; ?>
			</p>
			<form method="post" action="process.php">
				<ul class="choices">
					<?php while($row = $choices->fetch_assoc()): ?>

					<li>
						<input type="radio" name="choice" value="<?php echo $row['Id']; ?>" > <?php echo $row['text']; ?>
					</li>
					<?php endwhile; ?>
				</ul>
				<input type="submit" value="Submit" id="submit">
				<input type="hidden" name="number" value="<?php echo $number; ?>">
			</form>
		</div>
		<div class="logout">
			<a href="login.php"><b>LOGOUT</b></a>
		</div>
	</main>
	<footer>
		<div class="container">
			Copyright &copy Vaibhav-2019.
		</div>
	</footer>
</body>
</html>