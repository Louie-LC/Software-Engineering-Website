<?php 
include ('includes/session.php');
$page_title = 'Sign In';
$errors = "";

if (isset($_POST['submitted'])) {

    require_once ('login_functions.inc.php');
    require_once ('mysqli_connect.php');
    list ($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);

    if ($check) { // OK!
            session_save_path('');
            // Set the session data:.
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['first_name'] = $data['first_name'];
            $_SESSION['last_name'] = $data['last_name'];
            $_SESSION['user_type_id'] = $data['user_type_id'];

            // Store the HTTP_USER_AGENT:
            $_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);

            // Redirect:
            $url = absolute_url ('index.php');
            header("Location: $url");
            exit();

    } else { // Unsuccessful!
		$errors = $data;
    }

    mysqli_close($dbc);	    
} // End of the main submit conditional.

include ('includes/header.php');

// Print any error messages, if they exist:
if (!empty($errors)) {
	echo '<div class="alert alert-danger">
				<strong>Error signing in.</strong><br />
	The following error(s) occurred:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
		}
	echo '</p><p>Please try again.</p></div>';
}     
?>
<form class="form-signin" role="form" action="login.php" method="post">
    <h2 class="form-signin-heading">Please sign in</h2>
    <input type="normal" class="form-control" placeholder="Email address" required autofocus name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
    <input type="password" class="form-control" placeholder="Password" required name="pass">
    <label class="checkbox">
      <input type="checkbox" value="remember-me"> Remember me
    </label>
    <button class="btn btn-sm btn-primary" type="submit" name="submit">Sign in</button>
    <input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include ('includes/footer.html');
?>