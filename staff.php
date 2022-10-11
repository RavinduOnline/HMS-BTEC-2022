<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])|| 'admin' != $_SESSION['access']) {
		header('Location: login.php');
	}

    $staffs_list = '';

	// getting the list
	$query = "SELECT * FROM staffs WHERE  isDeleted = false ORDER BY create_datetime DESC";
	$staffs = mysqli_query($connection, $query);

	verify_query($staffs);
		while ($staff = mysqli_fetch_assoc($staffs)) {
			$staffs_list .= "<tr>";
			$staffs_list .= "<td>{$staff['id']}</td>";
			$staffs_list .= "<td>{$staff['name']}</td>";
            $staffs_list .= "<td>{$staff['nic']}</td>";
			$staffs_list .= "<td>{$staff['type']}</td>";
			$staffs_list .= "<td>
                                 <div class='action-container'>
                                    <a class='edit-button' href=\"modify-staff.php?user_id={$staff['id']}\">Edit &nbsp <i class='fa-solid fa-pen-to-square'></i></a>
                                    <a class='delete-button' href=\"delete-staff.php?user_id={$staff['id']}\"  onclick=\"return confirm('Are you sure?');\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
                                  </div>
                              </td>";
			$staffs_list .= "</tr>";
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
    <title>Manage Staff - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Staff Management</h1>
            <hr/>

            <div>
                <div class="viewpage-top-container">
                    <a href="add-staff.php" class="add-new-button">Add New Staff Member &nbsp<i class="fa-solid fa-plus"></i></a>
                </div>
                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Role</th>
                            <th id="action-col">Action</th>
                        </tr>
                        
                        <?php echo $staffs_list; ?>
                </table>
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>