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
    $ward = '';
    $password = '';

	if (isset($_POST['submit'])) {
        
        $name = $_POST['name'];
        $position = $_POST['position'];
        $nic = $_POST['nic'];
        $ward = $_POST['ward'];
        $password = $_POST['password'];

		// checking required fields
		$req_fields = array('name', 'position', 'nic', 'password');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('name' => 100, 'position' =>100, 'nic' => 12, 'ward' => 3,'password' => 20);
        $errors = array_merge($errors, check_max_len($max_len_fields));


        if (empty($errors)) {
			// no errors found... adding new record
			$name = mysqli_real_escape_string($connection, $_POST['name']);
			$position = mysqli_real_escape_string($connection, $_POST['position']);
			$nic = mysqli_real_escape_string($connection, $_POST['nic']);
            $ward = mysqli_real_escape_string($connection, $_POST['ward']);
            $password = mysqli_real_escape_string($connection, $_POST['password']);

            if(trim($ward) == ''){
                $ward = '0';
            }
            // encrypt password
			$hashed_password = sha1($password);

			$query = "INSERT INTO `doctors` (`id`, `password`, `name`, `nic`, `type`, `wardNo`, `create_datetime`) VALUES (NULL, '{$hashed_password}', '{$name}', '{$nic}', '{$position}', '{$ward}', current_timestamp())";

			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header('Location: doctor.php?doctor_added=true');
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
    <link rel="stylesheet" href="./CSS/doctor.css">

    <!--load icon styles -->
    <script src="https://kit.fontawesome.com/853b48ffc0.js" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Doctor - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title"><i class="fa-solid fa-user-doctor"></i> &nbsp Add New Doctor</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="doctor.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Doctor List</a>
                </div>

                <div class="form-container">
                    <form action="add-doctor.php" method="post" class="form-box">
                        <div>
                            <label>Name:</label>
                            <br/>
                            <input type="text" name="name" placeholder="Entre Name" maxlength="100"   <?php echo 'value="' . $name . '"'; ?>required>
                        </div>

                        <div>
                            <label>Position:</label>
                            <br/>
                            <input type="text" name="position" placeholder="Entre Position" maxlength="100"  <?php echo 'value="' . $position . '"'; ?> required>
                        </div>

                        <div>
                            <label>NIC No:</label>
                            <br/>
                            <input type="text" name="nic" placeholder="Entre NIC Number"   <?php echo 'value="' . $nic . '"'; ?> >
                        </div>
                        <div>
                            <label>Ward No:</label>
                            <br/>
                            <input type="number" name="ward" placeholder="Entre Ward Number"   <?php echo 'value="' . $ward . '"'; ?>>
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