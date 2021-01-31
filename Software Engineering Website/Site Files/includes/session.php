<?php
	session_start(); 
	$user_name = "CS-319";
	$is_admin = false;
	
	if (isset($_SESSION['user_id'])) {
		$user_name = 'Hello ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
		
		if(isset($_SESSION['user_type_id']) && $_SESSION['user_type_id'] == 3) {
			$is_admin = true;
		}
	}		
?>