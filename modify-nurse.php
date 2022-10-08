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
    $oldPassword = '';
    $user_id = '';
    $isActive = '';
    $isActiveStatus = '';

    if (isset($_GET['user_id'])) {
		// getting the user information
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
		$query = "SELECT * FROM nurses WHERE id = {$user_id} LIMIT 1";

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
				header('Location:nurse.php?err=user_not_found');	
			}
		} else {
			// query unsuccessful
			header('Location: nurse.php?err=query_failed');
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
		$max_len_fields = array('name' => 100, 'position' =>100, 'nic' => 12, 'isActive' => 1  , 'password' => 20);
        $errors = array_merge($errors, check_max_len($max_len_fields));

         // checking if NIC  already exists
		$nic = mysqli_real_escape_string($connection, $_POST['nic']);
		$query = "SELECT * FROM nurses WHERE nic = '{$nic}' AND id != {$user_id} LIMIT 1";

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

            
            if(!empty(trim($password))){  
                // encrypt password
                $hashed_password = sha1($password);
           }
           else{
               $hashed_password =  $oldPassword;
           }


			$query = "UPDATE `nurses` SET `password`='{$hashed_password}', `name`='{$name}', `type`='{$position}', `nic`='{$nic}' , `isActive`='{$isActive}' WHERE `nurses`.`id` = {$user_id}";


			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header('Location: nurse.php?nurse_modify=true');
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
    <title>Modify Nurse - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Modify Nurse</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="nurse.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Nurses List</a>
                </div>

                <div class="form-container">
                    <form action="modify-nurse.php" method="post" class="form-box">
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
                            <button type="submit" name="submit"><i class="fa-solid fa-floppy-disk"></i>&nbsp Modify</button>
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