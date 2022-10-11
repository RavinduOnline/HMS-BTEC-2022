<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'nurse' != $_SESSION['access']) {
		header('Location: login.php');
	}

    $prescription_list = '';
    $name = '';

	// getting the list
	$query = "SELECT * FROM prescriptions WHERE isIssued=false ";
	$prescriptions = mysqli_query($connection, $query);

	verify_query($prescriptions);
		while ($prescription = mysqli_fetch_assoc($prescriptions)) {
			$prescription_list .= "<tr>";
			$prescription_list .= "<td>{$prescription['id']}</td>";

            if(isset($prescription['patientsid'])){
                $query2 = "SELECT first_name , last_name FROM patients WHERE id={$prescription['patientsid']} LIMIT 1 ";
                $user_result = mysqli_query($connection, $query2);
                $users = mysqli_fetch_assoc($user_result);
                $name = $users['first_name'] .' '. $users['last_name']; 
            }
                    
			$prescription_list .= "<td >{$name}</td>";
            $prescription_list .= "<td id='text-centre'>{$prescription['created_at']}</td>";
			$prescription_list .= "<td>
                                 <div class='action-container'>
                                    <a class='edit-button' href=\"issue-medicine.php?prescription_id={$prescription['id']}&name=$name&date={$prescription['created_at']}&doc={$prescription['docid']}\">Issue Medicine &nbsp <i class='fa-solid fa-pills'></i></a>
                                  </div>
                              </td>";
			$prescription_list .= "</tr>";
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
    <title>Manage Prescription - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Prescription Management</h1>
            <hr/>

            <div>
                <div class="viewpage-top-container">
                </div>
                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Patient Name</th>
                            <th>Issued Date & Time</th>
                            <th id="action-col">Action</th>
                        </tr>
                        
                        <?php echo $prescription_list; ?>
                </table>
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>