<?php
	include ('includes/session.php');
	include ('./includes/header.php');
	require_once ('mysqli_connect.php');
?>
<?php
// start session
session_start();

if($_SESSION['user_type_id'] !== '1'){
    // isn't admin, redirect them to a different page
	echo "You need to be an administrator to perform this action!";
	echo "<script>setTimeout(\"location.href = 'http://cs.neiu.edu/~cs319_1_spr2018_group8/video_library_page.php';\",3000);</script>";
	exit();
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require_once ('mysqli_connect.php');
		
	$errors = array();
	
	if (empty($_POST['video_title'])) {
		$errors[] = 'The video title field cannot be empty.';
	} else {
		$title = mysqli_real_escape_string($dbc, trim($_POST['video_title']));
	}
	
	if (empty($_POST['video_tags'])) {
		$errors[] = 'The video tags field cannot be empty.';
	} else {
		$tags = mysqli_real_escape_string($dbc, trim($_POST['video_tags']));
	}
	
	if (empty($_POST['video_description'])) {
		$errors[] = 'The video description field cannot be empty.';
	} else {
		$description = mysqli_real_escape_string($dbc, trim($_POST['video_description']));
	}
	
	if (empty($_POST['video_link'])) {
		$errors[] = 'The video link field cannot be empty.';
	} else {
		$link = mysqli_real_escape_string($dbc, trim($_POST['video_link']));
	}
	
	if (empty($errors)) {
	
		
		$q = "INSERT INTO videos (video_title, video_tags, video_description, video_link) VALUES ('$title', '$tags', '$description', '$link')";
		$r = @mysqli_query ($dbc, $q);
		if ($r) {
		
			echo '<h1>Video uploaded successfully!</h1>
		<p>The video was successfully added to the video library.</p><p><br /></p>';	
		
		} else {
			
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
			
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		}
		
		mysqli_close($dbc);

		include ('includes/footer.html'); 
		exit();
		
	} else {
	
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) {
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	}
	
	mysqli_close($dbc);

}
?>
<div class="page-header">
    <h1>Upload Your Video</h1>
</div>
<div class="well">
	<form id="videoDetails" class="well" action="video_upload_page.php" method="post">
		<p>Video Title: <input type="text" name="video_title" size="25" maxlength="50" value="<?php if (isset($_POST['video_title'])) echo $_POST['video_title']; ?>" /></p>
		<p>Video Tags: <input type="text" name="video_tags" size="25" maxlength="50" value="<?php if (isset($_POST['video_tags'])) echo $_POST['video_tags']; ?>" /></p>
		<p>Video Description: <br><textarea class="text" cols="70" rows ="10" name="video_description" form="videoDetails"></textarea></p>
		<p>Video Link: <input type="text" name="video_link" value="<?php if (isset($_POST['video_link'])) echo $_POST['video_link']; ?>"  /></p>
		<p><input type="submit" name="submit" value="Upload" /></p>
	</form>
</div>
<?php include ('includes/footer.html'); ?>