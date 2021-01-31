<?php
	include ('includes/session.php');
	include ('./includes/header.php');
	require_once ('mysqli_connect.php');
?>

<form  method="post" action="video_library_page.php"  id="searchform"> 
	<input  type="text" name="title"> 
	<input  type="submit" name="search" value="Search"> 
</form>

<?php
	if (isset($_POST['search'])) {
		$word = $_POST['title'];
		$query1 = "SELECT * FROM videos WHERE video_title LIKE '%$word%'";
		$result = mysqli_query($dbc, $query1) or die ("Search unsuccessful");
		$track = mysqli_num_rows($result);
		if ($track == 0) {
			echo "No matches!";
		}else {
			echo "Matching results:" . "<br>";
			$x = 1;
			while($row = mysqli_fetch_array($result)) {
				echo $x . ". " . "<a href='"."video_page.php?id=".$row['video_id']."'>".$row['video_title']."</a>"."<br>";
				$x++;
			}
		}
	}
?>

<div class="page-header">
    <h1>Video Library</h1>
</div>
<div class="well">
	<label class="btn-default">
		<a href ="video_upload_page.php">Upload Video</a></li>
	</label>
</div>
<?php
	$query2 = "SELECT * FROM videos";
	$result = mysqli_query($dbc, $query2) or die ("Search unsuccessful");
	$track = mysqli_num_rows($result);
	if ($track == 0) {
		echo "No Videos!";
	}else {
		echo "<b>" . "Available videos:" . "</b>" . "<br>" . "<br>";
		$x = 1;
		while($row = mysqli_fetch_array($result)) {
			echo $x . ". " . "<b>Title: </b>" . "<a href='"."video_page.php?id=".$row['video_id']."'>".$row['video_title']."</a>"."<br>";
			$x++;
		}
	}
	mysqli_close($dbc);
?>

<?php include ('includes/footer.html'); ?>