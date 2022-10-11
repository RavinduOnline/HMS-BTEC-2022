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

		if ( $user_id == $_SESSION['user_id'] ) {
			// should not delete current user
			header('Location: admin.php?err=cannot_delete_current_user');
		} else {
			// deleting the user
			$query = "UPDATE admins SET isDeleted = 1 WHERE id = {$user_id} LIMIT 1";

			$result = mysqli_query($connection, $query);

			if ($result) {
				// user deleted
				header('Location: admin.php?msg=user_deleted');
			} else {
				header('Location: admin.php?err=delete_failed');
			}
		}
		
	} else {
		header('Location: admin.php');
	}
?>