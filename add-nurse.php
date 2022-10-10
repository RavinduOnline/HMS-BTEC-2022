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
    $position = '';
    $nic = '';
    $password = '';
    $ward_list  = '';
    $ward_no = '';

    $query = "SELECT * FROM `wards` WHERE isDeleted = false";
    $ward_result = mysqli_query($connection, $query);

    verify_query($ward_result);
		while ($ward = mysqli_fetch_assoc($ward_result)) {
			$ward_list .= "<option value='{$ward['id']}'>{$ward['id']} </option>";
	}


	if (isset($_POST['submit'])) {
        
        $name = $_POST['name'];
        $position = $_POST['position'];
        $nic = $_POST['nic'];
        $password = $_POST['password'];
        $ward_no = $_POST['wardNo'];

		// checking required fields
		$req_fields = array('name', 'position', 'nic', 'wardNo' , 'password');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('name' => 100, 'position' =>100, 'nic' => 12, 'password' => 20);
        $errors = array_merge($errors, check_max_len($max_len_fields));

        // checking if NIC  already exists
		$nic = mysqli_real_escape_string($connection, $_POST['nic']);
		$query = "SELECT * FROM nurses WHERE nic = '{$nic}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'NIC already exists';
			}
		}

        if (empty($errors)) {
			// no errors found... adding new record
			$name = mysqli_real_escape_string($connection, $_POST['name']);
			$position = mysqli_real_escape_string($connection, $_POST['position']);
			$nic = mysqli_real_escape_string($connection, $_POST['nic']);
            $password = mysqli_real_escape_string($connection, $_POST['password']);
            $ward_no = mysqli_real_escape_string($connection, $_POST['wardNo']);

            
            // encrypt password
			$hashed_password = sha1($password);

            if($ward_no == 'null'){
                $query = "INSERT INTO `nurses` (`id`, `password`, `name`, `type`, `nic` , `wardNo` ,`create_datetime`) VALUES (NULL, '{$hashed_password}', '{$name}','{$position}' , '{$nic}', NULL , current_timestamp())";
            }
            else{
                $query = "INSERT INTO `nurses` (`id`, `password`, `name`, `type`, `nic` , `wardNo` ,`create_datetime`) VALUES (NULL, '{$hashed_password}', '{$name}','{$position}' , '{$nic}', {$ward_no} , current_timestamp())";
            }

			

			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header('Location: nurse.php?nurse_added=true');
			} else {
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

    <!--load icon styles -->
    <script src="https://kit.fontawesome.com/853b48ffc0.js" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Nurse - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Add New Nurse</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="nurse.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Nurses List</a>
                </div>

                <div class="form-container">
                    <form action="add-nurse.php" method="post" class="form-box">
                        <div>
                            <label>Name:</label>
                            <br/>
                            <input type="text" name="name" placeholder="Entre Name" maxlength="100"   <?php echo 'value="' . $name . '"'; ?>required>
                        </div>

                        <div>
                            <label>Position:</label>
                            <br/>
                            <select name="position" required>
                                    <option value="" selected hidden>Select Position</option>
                                    <option value="Junior Nurse">Junior Nurse</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Senior Nurse">Senior Nurse</option>
                            </select>
                        </div>

                        <div>
                            <label>NIC No:</label>
                            <br/>
                            <input type="text" name="nic" placeholder="Entre NIC Number"   <?php echo 'value="' . $nic . '"'; ?> >
                        </div>

                        <div>
                            <label>Ward No:</label>
                            <br/>
                            <select name="wardNo" required>
                                <option value="" selected hidden>Select Ward</option>
                                <?php echo $ward_list; ?>
                                <option value="null">No Assigned Ward</option>
                            </select>
                        </div>

                        <div>
                            <label>New Password:</label>
                            <br/>
                            <input type="password" name="password" placeholder="Entre Password"  required>
                        </div>
                            <br/><br/>
                        <div class="submit-button-container">
                            <button type="submit" name="submit">Add</button>
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