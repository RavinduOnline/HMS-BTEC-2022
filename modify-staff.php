<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'admin' != $_SESSION['access']) {
		header('Location: login.php');
	}
    
    $errors = array();
    $name = '';
    $position = '';
    $nic = '';
    $password = '';
    $oldPassword = '';
    $user_id = '';
    $isActive = '';
    $isActiveStatus = '';


    if (isset($_GET['user_id'])) {
		// getting the user information
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
		$query = "SELECT * FROM staffs WHERE id = {$user_id} LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				// user found
				$result = mysqli_fetch_assoc($result_set);
                $user_id = $result['id'];;
                $name = $result['name'];
                $position = $result['type'];
                $nic =  $result['nic'];
                $oldPassword = $result['password'];
                $isActive = $result['isActive'];
                if($isActive){
                    $isActiveStatus = 'Active';
                }
                else{
                    $isActiveStatus = 'Deactivated';
                }
			} else {
				// user not found
				header('Location:staff.php?err=user_not_found');	
			}
		} else {
			// query unsuccessful
			header('Location: staff.php?err=query_failed');
		}
	}




	if (isset($_POST['submit'])) {
        
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $nic = $_POST['nic'];
        $oldPassword = $_POST['old_password'];
        $password = $_POST['new_password'];
        $isActive = $_POST['isActive'];

		// checking required fields
		$req_fields = array('name', 'position', 'nic');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('name' => 100, 'position' =>100, 'nic' => 12, 'password' => 20);
        $errors = array_merge($errors, check_max_len($max_len_fields));

        // checking if NIC  already exists
		$nic = mysqli_real_escape_string($connection, $_POST['nic']);
		$query = "SELECT * FROM staffs WHERE nic = '{$nic}' AND id != {$user_id} LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'NIC already exists';
			}
		}

        if (empty($errors)) {
			// no errors found... adding new record
            $user_id = mysqli_real_escape_string($connection, $_POST['user_id']);
			$name = mysqli_real_escape_string($connection, $_POST['name']);
			$position = mysqli_real_escape_string($connection, $_POST['position']);
			$nic = mysqli_real_escape_string($connection, $_POST['nic']);
            $password = mysqli_real_escape_string($connection, $_POST['new_password']);
            $oldPassword = mysqli_real_escape_string($connection, $_POST['old_password']);
            $isActive = mysqli_real_escape_string($connection,  $_POST['isActive']);

            
            // encrypt password
			$hashed_password = sha1($password);

			$query = "UPDATE `staffs` SET `password`='{$hashed_password}', `name`='{$name}', `type`='{$position}', `nic`='{$nic}' , `isActive`='{$isActive}' WHERE `staffs`.`id` = {$user_id}";

			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header('Location: staff.php?nurse_modify=true');
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
    <title>Modify Staff Member - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Modify Staff Member</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="staff.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Staff List</a>
                </div>

                <div class="form-container">
                    <form action="modify-staff.php" method="post" class="form-box">
                        <input <?php echo 'value="' . $user_id . '"'; ?> type="hidden" name="user_id">

                        <div>
                            <label>Name:</label>
                            <br/>
                            <input type="text" name="name" placeholder="Entre Name" maxlength="100"   <?php echo 'value="' . $name . '"'; ?>required>
                        </div>

                        <div>
                            <label>Position:</label>
                            <br/>
                            <select name="position" required>
                                    <?php echo "<option value='{$position}'  selected hidden>{$position}</option>"; ?>
                                    <option value="Officer">Officer</option>
                                    <option value="Medical Records Officer">Medical Records Officer</option>
                                    <option value="Auditor">Auditor</option>
                                    <option value="Matron">Matron</option>
                                    <option value="Senior Nurse">Assistant Officer</option>
                                    <option value="General Staff Member">General Staff Member</option>
                            </select>
                        </div>

                        <div>
                            <label>NIC No:</label>
                            <br/>
                            <input type="text" name="nic" placeholder="Entre NIC Number"   <?php echo 'value="' . $nic . '"'; ?> >
                        </div>

                        <div>
                            <label>Account Status:</label>
                            <br/>
                            <select name="isActive" value='saab' >
                                <?php echo "<option value='{$isActive}'  selected hidden>{$isActiveStatus}</option>"; ?>
                                <option value="1">Activate</option>
                                <option value="0">Deactivate</option>
                            </select>
                        </div>

                        <div>
                            <label>Password:</label>
                            <br/>
                            <input  type="password" name="old_password" <?php echo 'value="' . $oldPassword . '"'; ?> READONLY>
                        </div>

                        <div>
                            <label>New Password:</label>
                            <br/>
                            <input type="password" name="new_password" placeholder="Entre Password"  >
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