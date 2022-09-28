<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}

    $doctors_list = '';

	// getting the list of doctors
	$query = "SELECT * FROM doctors ORDER BY datetime DESC";
	$doctors = mysqli_query($connection, $query);

	if ($doctors) {
		while ($doctor = mysqli_fetch_assoc($doctors)) {
			$doctors_list .= "<tr>";
			$doctors_list .= "<td>{$doctor['id']}</td>";
			$doctors_list .= "<td>{$doctor['name']}</td>";
			$doctors_list .= "<td>{$doctor['type']}</td>";
			$doctors_list .= "<td><a href=\"modify-user.php?user_id={$doctor['id']}\">Edit</a>
                                  <a href=\"delete-user.php?user_id={$doctor['id']}\">Delete</a></td>";
			$doctors_list .= "</tr>";
		}
	} else {
		echo "Database query failed.";
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
    <title>Manage Doctors - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Doctors Management</h1>
            <hr/>

            <div>
                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                        
                        <?php echo $doctors_list; ?>
                </table>
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>