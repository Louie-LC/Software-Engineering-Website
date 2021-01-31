<?php
	include ('includes/session.php');
	include ('./includes/header.php');
	require_once ('mysqli_connect.php');
?>
<h1>Video Page</h1>
<?php
	$vidID = $_GET['id'];
	$userID = $_SESSION['user_id'];
	$query1 = "SELECT * FROM videos WHERE video_id = $vidID";
	$result = mysqli_query($dbc, $query1) or die("Unsuccessful fetch");
	$row = mysqli_fetch_array($result);
	Echo "<b>"."Title: "."</b>".$row['video_title']."<br>"."<br>";
	Echo "<b>"."Tags: "."</b>".$row['video_tags']."<br>"."<br>";
	Echo "<b>"."Description: "."</b>".$row['video_description']."<br>"."<br>";
	Echo "<b>"."Link: "."</b>".$row['video_link']."<br>";
?>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['rateVideo'])) {
			$vidID = $_GET['id'];
			$userID = $_SESSION['user_id'];
			$vRating = $_POST['rating'];
			$sql = "SELECT * FROM video_ratings WHERE user_id = $userID AND video_id = $vidID";
			$result = mysqli_query($dbc, $sql) or die("Unsuccessful query!");
			$track = mysqli_num_rows($result);
			if ($track == 0){
				$query = "INSERT INTO video_ratings (video_id, user_id, rating, rated) VALUES ('$vidID', '$userID', '$vRating', '1')";
				$r = mysqli_query ($dbc, $query) or die("Unsuccessful insertion");
			}
			else{
				echo "<font color='red'>"."You have already rated the video!"."</font>";
			}
		}
	}
?>
<br>
<form name="ratingForm" action="" method="post">
	<select name="rating">
		<option value="1">1 Star</option>
		<option value="2">2 Stars</option>
		<option value="3">3 Stars</option>
		<option value="4">4 Stars</option>
		<option value="5" selected>5 Stars</option>
		<p><input type="submit" name="rateVideo" value="Rate Video" /></p>
	 </select>
</form>
<?php
	$vidID = $_GET['id'];
	$query2 = "SELECT AVG(rating) AS avg FROM video_ratings WHERE video_id = $vidID";
	$result = mysqli_query($dbc, $query2) or die ("Unsuccessful data retrieval!");
	$avgRating = mysqli_fetch_assoc($result);
	$pVal = $avgRating['avg'];
	echo "Video rating: ".intval($pVal)." star/stars.";
?>
<div>
	<form id="commentSub" action="" method="post">
		<p>Leave a comment: <br><textarea class="text" cols="55" rows ="8" name="comment" form="commentSub"></textarea></p>
		<p><input type="submit" name="commentIt" value="Post Comment" /></p>
	</form>
	<hr>
</div>
<?php
	$vidID = $_GET['id'];
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['commentIt'])){
			$userID = $_SESSION['user_id'];
			if (empty($_POST['comment'])) {
				echo "<font color='red'>"."You cannot leave an empty comment."."</font>"."<br>"."<br>";
			} else {
				$comment = mysqli_real_escape_string($dbc, trim($_POST['comment']));
				$query = "INSERT INTO video_comments (video_id, user_id, comment) VALUES ('$vidID', '$userID', '$comment')";
				$r = mysqli_query ($dbc, $query) or die("Unsuccessful insertion");
			}
		}
	}
?>
<?php
	$vidID = $_GET['id'];
	$query2 = "SELECT comment, first_name, users.user_id, video_comments.user_id FROM video_comments JOIN users ON users.user_id = video_comments.user_id WHERE video_id = $vidID";
	$result = mysqli_query($dbc, $query2) or die ("Search unsuccessful");
	$track = mysqli_num_rows($result);
	if ($track == 0) {
		echo "No comments!";
	}else {
		echo "<b>" . "Video comments:" . "</b>" . "<br>" . "<hr>";
		while($row = mysqli_fetch_array($result)) {
			$fName = $row['first_name'];
			echo "<b>".$fName."</b>".": ".$row['comment']."<br>"."<br>";
		}
	}
	mysqli_close($dbc);
?>
<?php include ('includes/footer.html'); ?>