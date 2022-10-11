<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}

	if (isset($_GET['ward_id']) && 'admin' == $_SESSION['access']) {
		// getting the user information
		$ward_id = mysqli_real_escape_string($connection, $_GET['ward_id']);

        $query = "SELECT bedID FROM `patients` WHERE wardNo={$ward_id} AND isAdmit=true AND isDeleted=false";
        $result = mysqli_query($connection, $query);

        $Active_Patients = mysqli_num_rows( $result );


		if ($Active_Patients > 0) {
			// should not delete current user
			header('Location: ward.php?err=cannot_delete_ward_has_patients');
		} else {
			// deleting the ward
			$ward_id = mysqli_real_escape_string($connection, $_GET['ward_id']);
			$query = "UPDATE wards SET isDeleted = 1 WHERE id = {$ward_id} LIMIT 1";
			$result = mysqli_query($connection, $query);

            if ($result) {
                //Get bed count
				$query = "SELECT * FROM `beds` WHERE wardNo={$ward_id}";
			    $result2 = mysqli_query($connection, $query);
                $beds = mysqli_num_rows( $result2 );
	
				

				$errors =0;
                $x=1;
				if($result2){
					while($beds >= $x){
						$query = "UPDATE beds SET isDeleted =1 WHERE bedNo ={$x} AND wardNo={$ward_id} LIMIT 1";
						$result3 = mysqli_query($connection, $query);

						if ($result3 || $errors > 3) {
							$x++;
							$errors=0;
						}
						$errors++;
					}

					if ($result3) {
						// user deleted
						header('Location: ward.php?msg=ward_deleted');
					} else {
						header('Location: ward.php?err=loop_delete_failed');
					}
				}
				else{
					header('Location: ward.php?err=bed_delete_failed');
				}

			} else {
				header("Location: ward.php?err=delete_failed_A_$result");
			}
            
			
		}
		
	} else {
		header('Location: admin.php');
	}
?>