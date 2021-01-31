<?php # Script 8.7 - password.php
include ('includes/session.php');

// This page lets a user change their password.

$page_title = 'Change Your Password';
include ('includes/header.php');

// Check if the form has been submitted:
if (isset($_POST['submitted'])) {

	require_once ('mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	// Check for an user:
	if (!isset($_SESSION['user_id'])) {
		$errors[] = 'You must be first signed in to change your password.';
	} else {
		$e = $_SESSION['user_id'];
	}
	        
	// Check for the current password:
	if (empty($_POST['pass'])) {
		$errors[] = 'You forgot to enter your current password.';
	} else {
		$p = mysqli_real_escape_string($dbc, trim($_POST['pass']));
	}

	// Check for a new password and match 
	// against the confirmed password:
	if (!empty($_POST['pass1'])) {
		if ($_POST['pass1'] != $_POST['pass2']) {
			$errors[] = 'Your new password did not match the confirmed password.';
		} else {
			$np = mysqli_real_escape_string($dbc, trim($_POST['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your new password.';
	}
	
	if (empty($errors)) { // If everything's OK.
	
		// Check that they've entered the right email address/password combination:
		$q = "SELECT user_id FROM users WHERE (user_id=$e AND pass=SHA1('$p') )";
		$r = @mysqli_query($dbc, $q);
		$num = @mysqli_num_rows($r);
		if ($num == 1) { // Match was made.
		
			// Get the user_id:
			$row = mysqli_fetch_array($r, MYSQLI_NUM);

			// Make the UPDATE query:
			$q = "UPDATE users SET pass=SHA1('$np') WHERE user_id=$row[0]";		
			$r = @mysqli_query($dbc, $q);
			
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
			
				// Print a message.
				echo '<h1>Thank you!</h1>
				<p>Your password has been updated.</p><p><br /></p>';	
			
			} else { // If it did not run OK.
			
				// Public message:
				echo '<h1>System Error</h1>
				<p class="error">Your password could not be changed due to a system error. We apologize for any inconvenience.</p>'; 
				
				// Debugging message:
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
				
			}

			// Include the footer and quit the script (to not show the form).
			include ('includes/footer.html'); 
			exit();
				
		} else { // Invalid email address/password combination.
			echo '<h1>Error!</h1>
			<p class="error">The email address and password do not match those on file.</p>';
		}
		
	} else { // Report the errors.
	
		// Print any error messages, if they exist:
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">
                            <strong>Error changing password.</strong><br />
                    The following error(s) occurred:<br />';
                    foreach ($errors as $msg) {
                            echo " - $msg<br />\n";
                    }
                    echo '</p><p>Please try again.</p></div>';
                }
		
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
		
}
?>

<form class="form-signin" role="form" action="password.php" method="post">
    <h2 class="form-signin-heading">Change Your Password</h2>
    <input type="password" class="form-control" placeholder="Current Password" required name="pass">
    <input type="password" class="form-control" placeholder="New Password" required name="pass1">
    <input type="password" class="form-control" placeholder="New Password" required name="pass2">
    <button class="btn btn-sm btn-primary" type="submit" name="submit">Change Password</button>
    <input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('includes/footer.html');
?>
