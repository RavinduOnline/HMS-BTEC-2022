<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>



<?php 
	// checking if a user is logged in
	if (isset($_SESSION['user_id'])) {
		header('Location: index.php');
	}
 ?>

<?php 

	// check for form submission
	if (isset($_POST['submit'])) {

		$errors = array();

		// check if the username / password / user type has been entered
		if (!isset($_POST['username']) || strlen(trim($_POST['username'])) < 1 ) {
			$errors[] = 'Username is Missing / Invalid';
		}

		if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1 ) {
			$errors[] = 'Password is Missing / Invalid';
		}
		if (!isset($_POST['usertype']) || strlen(trim($_POST['usertype'])) < 1 ) {
			$errors[] = 'User type is Missing / Invalid';
		}

		// Check if username  and user type are in correct format
		if(trim($_POST['usertype']) != 3 &&  ! ctype_digit(strval(trim($_POST['username']))) ){
			$errors[] = 'User type is Invalid or Username is Invalid ';
		}

		// check if there are any errors in the form
		if (empty($errors)) {
			// save username, password and user type into variables
			$usertype 	= mysqli_real_escape_string($connection, $_POST['usertype']);
			$username 	= mysqli_real_escape_string($connection, $_POST['username']);
			$password 	= mysqli_real_escape_string($connection, $_POST['password']);
			$hashed_password = sha1($password); 

			// prepare database query according to user type
			switch ($usertype) {
				case 1:
					$query = "SELECT * FROM doctors 
							  WHERE id = $username 
							  AND
							  isActive = true
							  AND 
							  isDeleted != true
							  AND
							  password = '{$hashed_password}'  
							  LIMIT 1
							  ";
					$userlevel = "doctor";
				  break;
				case 2:
					$query = "SELECT * FROM nurses 
							  WHERE id = $username 
							  AND
							  isActive = true
							  AND 
							  isDeleted != true
							  AND 
							  password = '{$hashed_password}'  
							  LIMIT 1
							  ";
					$userlevel = "nurse";
				  break;
				case 3:
					$query = "SELECT * FROM admins 
							  WHERE username = '{$username}' 
							  AND
							  isActive = true
							  AND 
							  isDeleted != true
							  AND 
							  password = '{$hashed_password}'  
							  LIMIT 1
							  ";
					$userlevel = "admin";
				  break;
				case 4:
					$query = "SELECT * FROM staffs 
						      WHERE id = $username 
							  AND
							  isActive = true
							  AND 
							  isDeleted != true
							  AND 
							  password = '{$hashed_password}'  
							  LIMIT 1
							 ";
					$userlevel = "staff";
					break;
				default:
				  	echo "Something went wrong";
			  }

			$result_set = mysqli_query($connection, $query);

			verify_query($result_set);
				// query succesfful
                // echo "<script>console.log($result_set );</script>";

				if (mysqli_num_rows($result_set) == 1) {
					// valid user found
					$user = mysqli_fetch_assoc($result_set);
					$_SESSION['user_id'] = $user['id'];
					$_SESSION['name'] = $user['name'];
					$_SESSION['role'] = $user['type'];
					$_SESSION['access'] = $userlevel;
					// redirect to users.php
					header('Location: index.php');
				} else {
					// user name and password invalid
					$errors[] = "Invalid Username / Password or You'r Account is Deactivated";
				}
		}
	}
?>

<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/login.css">


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log In - HMS</title>
</head>
<body class="login-body">
			<div class="system-logo">
				<img src="./images/logo/hospital-management-system-logo-dark.png">
			</div>
            <div class="login">

                    <form action="login.php" method="post">
                        <fieldset class="l">
                            <legend><h1>HMS Log In</h1></legend>

                            <?php 
                                if (!empty($errors)) {
									display_errors($errors);
									echo '<br/>';
								}

								if (isset($_GET['logout'])) {
									echo '<p class="info">✓ You have successfully logged out from the system</p>';
								}
                            ?>
								<label>User Type:</label>
									<select  class="login-input" name="usertype" >
										<option value="" selected hidden>Select User Type</option>
										<option value="1">Doctor</option>
										<option value="2">Nurse</option>
										<option value="3">Admin</option>
										<option value="4">Other Staff</option>
									</select>
									<br/>

                                <label>Username:</label>
                                <input class="login-input" type="text" name="username" id="" placeholder="Username">
                                <br/>

                                <label>Password:</label>
                                <input class="login-input" type="password" name="password" id="" placeholder="Password">
                                <br/><br/>

                                <button class="login-button" type="submit" name="submit">Log In</button>
                        </fieldset>
                    </form>		

                </div> <!-- .login -->    
</body>
</html>

<?php mysqli_close($conn); ?>