<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'admin' != $_SESSION['access'] ) {
		header('Location: login.php');
	}
    
	$errors = array();
    $name = '';
    $position = '';
    $nic = '';
    $password = '';
    $username = '';

	if (isset($_POST['submit'])) {
        
        $name = $_POST['name'];
        $position = $_POST['position'];
        $nic = $_POST['nic'];
        $username = $_POST['username'];
        $password = $_POST['password'];

		// checking required fields
		$req_fields = array('name', 'position', 'nic', 'username' ,'password');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('name' => 100, 'position' =>100, 'nic' => 12, 'username' => 25, 'password' => 20);
        $errors = array_merge($errors, check_max_len($max_len_fields));

         // checking if NIC  already exists
		$nic = mysqli_real_escape_string($connection, $_POST['nic']);
		$query = "SELECT * FROM admins WHERE nic = '{$nic}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'NIC already exists';
			}
		}

        $username = mysqli_real_escape_string($connection,  trim($_POST['username']));
        $query = "SELECT * FROM admins WHERE username = '{$username}' LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'This Username is already taken';
			}
		}


        if (empty($errors)) {
			// no errors found... adding new record
			$name = mysqli_real_escape_string($connection, $_POST['name']);
			$position = mysqli_real_escape_string($connection, $_POST['position']);
			$nic = mysqli_real_escape_string($connection, $_POST['nic']);
            $username = mysqli_real_escape_string($connection, trim($_POST['username']));
            $password = mysqli_real_escape_string($connection, $_POST['password']);

            
            // encrypt password
			$hashed_password = sha1($password);

			$query = "INSERT INTO `admins` (`id`, `username`, `password`, `name`, `nic`, `type`,`create_datetime`) VALUES (NULL, '{$username}' ,'{$hashed_password}', '{$name}', '{$nic}' , '{$position}' , current_timestamp())";

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
    <title>Add New Administrator - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Add New Administrator</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="admin.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Admin List</a>
                </div>

                <div class="form-container">
                    <form action="add-admin.php" method="post" class="form-box">
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
                            <label>Username:</label>
                            <br/>
                            <input type="text" name="username" placeholder="Entre Username" maxlength="100"   <?php echo 'value="' . $username . '"'; ?>required>
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