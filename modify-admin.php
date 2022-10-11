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
    $username = '';
    $old_username = '';

    if (isset($_GET['user_id'])) {
		// getting the user information
		$user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
		$query = "SELECT * FROM admins WHERE id = {$user_id} LIMIT 1";

		$result_set = mysqli_query($connection, $query);

		if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				// user found
				$result = mysqli_fetch_assoc($result_set);
                $user_id = $result['id'];;
                $name = $result['name'];
                $position = $result['type'];
                $nic =  $result['nic'];
                $old_username = $result['username'];
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
				header('Location:admin.php?err=user_not_found');	
			}
		} else {
			// query unsuccessful
			header('Location: admin.php?err=query_failed');
		}
	}




	if (isset($_POST['submit'])) {
        
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $nic = $_POST['nic'];
        $old_username =  $_POST['old_username'];
        $username = $_POST['username'];
        $oldPassword = $_POST['old_password'];
        $password = $_POST['password'];
        $isActive = $_POST['isActive'];

		// checking required fields
		$req_fields = array('name', 'position', 'nic');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('name' => 100, 'position' =>100, 'nic' => 12, 'username' => 25, 'password' => 20);
        $errors = array_merge($errors, check_max_len($max_len_fields));

        // checking if NIC  already exists
		$nic = mysqli_real_escape_string($connection, $_POST['nic']);
		$query = "SELECT * FROM admins WHERE nic = '{$nic}' AND id != {$user_id} LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'NIC already exists';
			}
		}

        $username = mysqli_real_escape_string($connection,  trim($_POST['username']));

        if(!empty($username)){  
            $query = "SELECT * FROM admins WHERE username = '{$username}' LIMIT 1";
            $result_set = mysqli_query($connection, $query);

            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    $errors[] = 'This Username is already taken';
                }
            }
        }
        


		


        if (empty($errors)) {
			// no errors found... adding new record
            $user_id = mysqli_real_escape_string($connection, $_POST['user_id']);
			$name = mysqli_real_escape_string($connection, $_POST['name']);
			$position = mysqli_real_escape_string($connection, $_POST['position']);
			$nic = mysqli_real_escape_string($connection, $_POST['nic']);
            $old_username =  mysqli_real_escape_string($connection, $_POST['old_username']);
            $username = mysqli_real_escape_string($connection, trim($_POST['username']));
            $password = mysqli_real_escape_string($connection, trim($_POST['password']));
            $oldPassword =  mysqli_real_escape_string($connection, trim($_POST['old_password']));
            $isActive =  mysqli_real_escape_string($connection, $_POST['isActive']);
            $insert_username = '';

            if(!empty(trim($password))){  
                // encrypt password
                $hashed_password = sha1($password);
           }
           else{
               $hashed_password =  $oldPassword;
           }

           if(!empty($username)){  
                $insert_username = $username;
            }
            else{
                $insert_username = $old_username;
            }
        
        
            

			$query =  "UPDATE `admins` SET `username` = '{$insert_username}', `password` = '{$hashed_password}', `name` = '{$name}', `nic` = '{$nic}', `type` = '{$position}', `isActive` = '{$isActive}' WHERE `admins`.`id` = {$user_id}";

			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header('Location: admin.php?nurse_added=true');
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
    <title>Modify Administrator - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Modify Administrator</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="admin.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Admin List</a>
                </div>

                <div class="form-container">
                    <form action="modify-admin.php" method="post" class="form-box">
                       
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
                                    <option value="Admin">Admin</option>
                                    <option value="Senior Manager">Senior Manager</option>
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
                            <label>Current Username:</label>
                            <br/>
                            <input type="text" name="old_username" placeholder="Entre Username" maxlength="100"   <?php echo 'value="' . $old_username . '"'; ?> readonly>
                        </div>

                        <div>
                            <label>New Username:</label>
                            <br/>
                            <input type="text" name="username" placeholder="Entre Username" maxlength="100"   <?php echo 'value="' . $username . '"'; ?> >
                        </div>

                        <div>
                            <label>Current Password:</label>
                            <br/>
                            <input type="password" name="old_password" placeholder="Entre Password"  <?php echo 'value="' . $oldPassword . '"'; ?> readonly>
                        </div>

                        <div>
                            <label>New Password:</label>
                            <br/>
                            <input type="password" name="password" placeholder="Entre Password"  >
                        </div>
                            <br/><br/>
                        <div class="submit-button-container">
                            <button type="submit" name="submit">Modify</button>
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