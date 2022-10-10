<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}
    
	$errors = array();
    $name = '';
    $city = '';
    $wardNo = '';
    $bedID = '';
    $page = '';

    if (isset($_GET['user_id']) && isset($_GET['page'])) {

        $page = $_GET['page'];

        // getting the user information
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
		$query = "SELECT * FROM patients WHERE id = {$user_id} LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				// user found
				$result = mysqli_fetch_assoc($result_set);
                $user_id = $result['id'];
                $name = $result['first_name'] ."   ". $result['last_name'];
                $city = $result['city'];
                $wardNo = $result['wardNo'];
                $bedID = $result['bedID'];
			} else {
				// user not found
				header('Location:index.php?err=user_not_found');	
			}
		} else {
			// query unsuccessful
			header('Location:index.php?err=query_failed');
		}
    }


	if (isset($_POST['submit'])) {


        $user_id = $_POST['user_id'];
        $doc_id = $_SESSION['user_id'];
        $prescription = $_POST['prescription'];
        $page = $_POST['page'];

		// checking required fields
		$req_fields = array('user_id', 'user_id', 'prescription');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('prescription' => 1000);
        $errors = array_merge($errors, check_max_len($max_len_fields));

        if (empty($errors)) {
			// no errors found... adding new record
            $user_id = mysqli_real_escape_string($connection,$_POST['user_id']);
            $doc_id = mysqli_real_escape_string($connection,$_SESSION['user_id']);
            $prescription = mysqli_real_escape_string($connection,$_POST['prescription']);

            if($page != 'doc-ward-patient.php'){
                $query = "UPDATE `patients` SET `isUnderSupervision` = '0' WHERE `patients`.`id` = {$user_id}";
                $result = mysqli_query($connection, $query);
            }
            else{
                $result = true;
            }
                
            if($result){
                $query = "INSERT INTO `prescriptions` (`id`, `patientsid`, `docid`, `description`, `isIssued`, `created_at`) VALUES (NULL, {$user_id} , {$doc_id} , '{$prescription}', '0', current_timestamp())";

                $result = mysqli_query($connection, $query);

                if ($result) {
                    // query successful... redirecting 
                    header("Location:$page?nurse_added=true");
                } else {
                    $errors[] = 'Failed to add the new record.';
                }
            }else{
                $errors[] = 'Failed to add the new record.';
            }

                   


		}

	}



?>


<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/common.css">
    <link rel="stylesheet" href="./CSS/footer.css">
    <link rel="stylesheet" href="./CSS/ward.css">

    <!--load icon styles -->
    <script src="https://kit.fontawesome.com/853b48ffc0.js" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Prescription - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Give Prescription</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href=" <?php echo "$page" ?>" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back</a>
                </div>        
                
                <div class="ward-bed-top-details">
                    <h3>Name - <?php echo "$name" ?></h3>
                    <h3>City  - <?php echo "$city" ?></h3>
                    <h3>Ward No  -  <?php if($wardNo){echo $wardNo;} else{ echo "Not an inpatient";} ?></h3>
                    <h3>Bed No  -  <?php if($bedID) echo $bedID; else{ echo "Not an inpatient";}?></h3>
                </div>
                
               <div class="Prescription-container">
                        <form action='<?php echo "add-prescription.php?user_id=$user_id&page=$page"?>' method="post" >

                            <input <?php echo 'value="' . $user_id . '"'; ?> type="hidden" name="user_id">
                            <input <?php echo 'value="' . $page . '"'; ?> type="hidden" name="page">
                            
                            <label>Prescription:</label>

                                <p>Please separate the medicine from each other using a comma</p>
                                <p>please separate the medicine quantity from the medicine name using a Hyphen (-) Ex :- Omeprazole-1mg, Ventolin-100mg</p>

                            <textarea class="prescription-textarea" name="prescription"> <?php echo $prescription ?></textarea>
                            <br/>
                            <br/>
                            <div class="Prescription-button-box">
                                <button type="submit" name="submit">Create</button>
                            </div>
                            <?php 
                                if (!empty($errors)) {
                                    display_errors($errors);
                                }
                            ?>
                        </form>
               </div>

                


                
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>