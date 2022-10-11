<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	if (isset($_GET['user_id']) && 'admin' == $_SESSION['access']) {
		// getting the user information
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);

			// deleting the user
			$query = "UPDATE nurses SET isDeleted = 1 WHERE id = {$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);

			if ($result) {
				// user deleted
				header('Location: nurse.php?msg=user_deleted');
			} else {
				header('Location: nurse.php?err=delete_failed');
			}
		
		
	} else {
		header('Location: nurse.php');
	}
?>