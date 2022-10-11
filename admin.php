<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'admin' != $_SESSION['access']) {
		header('Location: login.php?access=false');
	}

    $error="";

    if (isset($_GET['err'])) {
            $error = "Cannot Delete Current User";
    }


    $admins_list = '';

	// getting the list of doctors
	$query = "SELECT * FROM admins WHERE isDeleted != true ORDER BY create_datetime DESC";
	$admins = mysqli_query($connection, $query);

	verify_query($admins);
		while ($admin = mysqli_fetch_assoc($admins)) {
			$admins_list .= "<tr>";
			$admins_list .= "<td>{$admin['id']}</td>";
			$admins_list .= "<td>{$admin['name']}</td>";
            $admins_list .= "<td>{$admin['nic']}</td>";
			$admins_list .= "<td>{$admin['type']}</td>";
			$admins_list .= "<td>
                                 <div class='action-container'>
                                    <a class='edit-button' href=\"modify-admin.php?user_id={$admin['id']}\">Edit &nbsp <i class='fa-solid fa-pen-to-square'></i></a>
                                    <a class='delete-button' href=\"delete-admin.php?user_id={$admin['id']}\" onclick=\"return confirm('Are you sure?');\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
                                  </div>
                              </td>";
			$admins_list .= "</tr>";
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
    <title>Manage Admins - HMS</title>
   
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Admin Management</h1>
            <hr/>

            <div>
                <div class="viewpage-top-container">
                    <a href="add-admin.php" class="add-new-button">Add New System Admin &nbsp<i class="fa-solid fa-plus"></i></a>
                </div>

                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>Role</th>
                            <th id="action-col">Action</th>
                        </tr>
                        
                        <?php echo $admins_list; ?>
                </table>
            </div>

            <?php if($error){
                echo  "<script type='text/javascript'>
                        alert('$error');
                    </script>";

            }?>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>