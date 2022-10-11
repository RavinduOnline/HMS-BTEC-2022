<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'staff' == $_SESSION['access']) {
		header('Location: login.php');
	}

    $nurses_list = '';

	// getting the list of doctors
	$query = "SELECT * FROM nurses WHERE isDeleted = false ORDER BY create_datetime DESC";
	$nurses = mysqli_query($connection, $query);

	verify_query($nurses);
		while ($nurse = mysqli_fetch_assoc($nurses)) {
			$nurses_list .= "<tr>";
			$nurses_list .= "<td>{$nurse['id']}</td>";
			$nurses_list .= "<td>{$nurse['name']}</td>";
            $nurses_list .= "<td>{$nurse['nic']}</td>";
			$nurses_list .= "<td>{$nurse['type']}</td>";
            $nurses_list .= "<td id='text-centre'>{$nurse['wardNo']}</td>";
            if($_SESSION['access'] == 'admin'){
                $nurses_list .= "<td>
                                    <div class='action-container'>
                                        <a class='edit-button' href=\"modify-nurse.php?user_id={$nurse['id']}\">Edit &nbsp <i class='fa-solid fa-pen-to-square'></i></a>
                                        <a class='delete-button' href=\"delete-nurse.php?user_id={$nurse['id']}\" onclick=\"return confirm('Are you sure?');\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
                                    </div>
                                </td>";
            }
			$nurses_list .= "</tr>";
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
    <title>Manage Nurses - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Nurses Management</h1>
            <hr/>

            <div>
                <div class="viewpage-top-container">
                             <?php 
                                  if($_SESSION['access'] == 'admin'){
                                    echo '<a href="add-nurse.php" class="add-new-button">Add New Nurse &nbsp<i class="fa-solid fa-plus"></i></a>';
                                  }
                            ?>
                </div>
                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Role</th>
                            <th>Ward No</th>
                            <?php 
                                  if($_SESSION['access'] == 'admin'){
                                    echo '<th id="action-col">Action</th>';
                                  }
                            ?>
                        </tr>
                        
                        <?php echo $nurses_list; ?>
                </table>
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>