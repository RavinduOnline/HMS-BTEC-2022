<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	if (isset($_GET['ward_id']) && isset($_GET['bed_id']) && 'admin' == $_SESSION['access']) {
		// getting the bed has patient
		$ward_id = mysqli_real_escape_string($connection, $_GET['ward_id']);
        $bed_id = mysqli_real_escape_string($connection, $_GET['bed_id']);

        $query = "SELECT isAdmit FROM `patients` WHERE wardNo={$ward_id} AND bedID = {$bed_id} AND isAdmit=true AND isDeleted=false";
        $result = mysqli_query($connection, $query);

        $Active_Patients = mysqli_num_rows( $result );


		if ($Active_Patients > 0) {
			// should not delete bed has patient
			header("Location: ward-bed.php?err=cannot_delete_bed_has_patients&ward_no=$ward_id");
		} else {
			// deleting the bed
			$ward_id = mysqli_real_escape_string($connection, $_GET['ward_id']);
			$query = "UPDATE beds SET isDeleted = 1 WHERE id = {$bed_id} AND wardNo = {$ward_id} LIMIT 1";
			$result = mysqli_query($connection, $query);

            if ($result) {
                // user deleted
                header("Location: ward-bed.php?msg=bed_deleted&ward_no=$ward_id");
            } else {
                header('Location: ward-bed.php?err=bed_delete_failed');
            }

		}
		
	} else {
		header('Location: ward-bed.php');
	}
?>