<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}

    $doctors_list = '';

	// getting the list of doctors
	$query = "SELECT * FROM patients ORDER BY create_datetime DESC";
	$doctors = mysqli_query($connection, $query);

	verify_query($doctors);
        $AdmitStatus = '';
		while ($doctor = mysqli_fetch_assoc($doctors)) {
			$doctors_list .= "<tr>";
			$doctors_list .= "<td>{$doctor['id']}</td>";
			$doctors_list .= "<td>{$doctor['first_name']} {$doctor['last_name']}</td>";
            $doctors_list .= "<td>{$doctor['nic']}</td>";
			$doctors_list .= "<td>{$doctor['city']}</td>";
            $doctors_list .= "<td>{$doctor['contact_no']}</td>";
            $doctors_list .= "<td>{$doctor['custodians_name']}</td>";
            $doctors_list .= "<td>{$doctor['custodians_contact_no']}</td>";
            if($doctor['isAdmit']){
                $AdmitStatus = 'Admitted';
            }
            else{
                $AdmitStatus = 'Not Admitted';
            }
            $doctors_list .= "<td>{$AdmitStatus}</td>";
			$doctors_list .= "<td>
                                 <div class='action-container'>
                                    <a class='edit-button' href=\"modify-user.php?user_id={$doctor['id']}\">Edit &nbsp <i class='fa-solid fa-pen-to-square'></i></a>
                                    <a class='delete-button' href=\"delete-user.php?user_id={$doctor['id']}\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
                                  </div>
                              </td>";
			$doctors_list .= "</tr>";
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
    <title>Manage Patients - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Patients Management</h1>
            <hr/>

            <div>
                <div class="viewpage-top-container">
                    <a href="add-patient.php" class="add-new-button">Add New Patient &nbsp<i class="fa-solid fa-plus"></i></a>
                </div>
                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>City</th>
                            <th>Contact No</th>
                            <th>Custodians Name</th>
                            <th>Custodians Contact No</th>
                            <th>Patient Status</th>
                            <th id="action-col">Action</th>
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