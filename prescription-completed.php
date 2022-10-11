<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	if (isset($_GET['prescription_id']) && 'nurse' == $_SESSION['access']) {
		// getting the user information
		$prescription_id = mysqli_real_escape_string($connection, $_GET['prescription_id']);

			// deleting the user
			$query = "UPDATE`prescriptions` SET `isIssued` = 1, `completed_at` = current_timestamp() WHERE `prescriptions`.`id` = {$prescription_id} LIMIT 1";

			$result = mysqli_query($connection, $query);

			if ($result) {
				// user deleted
				header('Location: prescription.php?msg=prescription_completed');
			} else {
				header('Location: prescription.php?err=prescription_complete_status_failed');
			}
		
		
	} else {
		header('Location: staff.php');
	}
?>