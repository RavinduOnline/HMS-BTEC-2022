<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'admin' != $_SESSION['access']) {
		header('Location: login.php');
	}

    $doctors_list = '';
    $errors = array();
    $ward_no = '';
    $head_doctor = '';
    $beds = '';

	// getting the list of doctors
	$query = "SELECT * FROM doctors WHERE isDeleted != true";
	$doctors = mysqli_query($connection, $query);

	verify_query($doctors);
		while ($doctor = mysqli_fetch_assoc($doctors)) {
			$doctors_list .= "<option value='{$doctor['id']}'>{$doctor['name']} - {$doctor['type']} </option>";
	}
    
    // getting the last ward number
    $query2 =  "SELECT id FROM wards ORDER BY create_datetime DESC LIMIT 1";
    $ward_number_result = mysqli_query($connection, $query2);
   
    if($ward_number_result){
        $wardNo = mysqli_fetch_assoc($ward_number_result);
        $ward_no = $wardNo['id'] + 1;
    }



	if (isset($_POST['submit'])) {
        
        $head_doctor = $_POST['head_doctor'];
        $ward_no = $_POST['ward_no'];
        $beds = $_POST['beds'];

		// checking required fields
		$req_fields = array('head_doctor' , "beds");
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('beds' => 2);
        $errors = array_merge($errors, check_max_len($max_len_fields));


        // checking if NIC  ward number exists
		$ward_no = mysqli_real_escape_string($connection, $_POST['ward_no']);
		$query = "SELECT id FROM wards WHERE id = {$ward_no} LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				header('Location: add-ward.php?ward_number_exists');
			}
		}

         // checking beds count
        if ($beds < 1) {
            $errors[] = "Number of Beds can't be zero or minus";
        }

        if (empty($errors)) {
			// no errors found... adding new record
            $head_doctor = mysqli_real_escape_string($connection, $_POST['head_doctor']);
            $beds = mysqli_real_escape_string($connection,$_POST['beds']);
            $ward_no = mysqli_real_escape_string($connection,$_POST['ward_no']);


			$query = "INSERT INTO `wards` (`id`, `headDocID`, `isDeleted`, `create_datetime`) VALUES (NULL, {$head_doctor}, '0', current_timestamp())";
			$result = mysqli_query($connection, $query);

            if($result){
                $x = 1;


                while($beds >= $x) {
                    $query = "INSERT INTO `beds` (`id`, `bedNo`, `wardNo`, `isDeleted`) VALUES (NULL, {$x}, {$ward_no} , '0')";
                    $result = mysqli_query($connection, $query);
                    if (!$result) {
                        // query successful... redirecting to doctor page
                        header('Location: ward.php?ward_added_has_error=true');
                    }
                    $x++;
                }

                if ($result) {
                    // query successful... redirecting to doctor page
                    header('Location: ward.php?ward_added=true?bed_added=true');
                } else {
                    $errors[] = 'Failed to add the new record.';
                }

            }




		}

	}



?>


<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/common.css">
    <link rel="stylesheet" href="./CSS/footer.css">
    <link rel="stylesheet" href="./CSS/doctor.css">

    <!--load icon styles -->
    <script src="https://kit.fontawesome.com/853b48ffc0.js" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Ward - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Add New Ward</h1> 
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="ward.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Ward Management</a>
                </div>

                <div class="form-container">
                    <form action="add-ward.php" method="post" class="form-box">
                        <div>
                            <label>New Ward No:</label>
                            <br/>
                            <input type="number" name="ward_no" placeholder="Entre Ward Number" maxlength="5"   <?php echo 'value="' . $ward_no . '"'; ?> readonly>
                        </div>

                        <div>
                            <label>Head Doctor:</label>
                            <br/>
                                <select name="head_doctor"   <?php echo 'value="' . $head_doctor . '"'; ?>>
                                    <option value="" selected hidden>Select Doctor</option>
                                    <?php echo $doctors_list; ?>
                                </select>
                        </div>

                        <div>
                            <label>Number of Beds:</label>
                            <br/>
                            <input type="number" name="beds" placeholder="Entre Beds Count" maxlength="2"   <?php echo 'value="' . $beds . '"'; ?> required>
                        </div>

                            <br/><br/>
                        <div class="submit-button-container">
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