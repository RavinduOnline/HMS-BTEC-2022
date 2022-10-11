

<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}

    $patients_list = '';
    $user_id = $_SESSION['user_id'];

    	// getting the list of patients
	$query = "SELECT * FROM patients WHERE supervisingDocID = $user_id AND isAdmit=false AND isUnderSupervision=true AND isDeleted=false ORDER BY create_datetime DESC";
	$patients = mysqli_query($connection, $query);

	verify_query($patients);
        $AdmitStatus = '';
		while ($patient = mysqli_fetch_assoc($patients)) {
			$patients_list .= "<tr>";
			$patients_list .= "<td>{$patient['id']}</td>";
			$patients_list .= "<td>{$patient['first_name']} {$patient['last_name']}</td>";
			$patients_list .= "<td>{$patient['city']}</td>";
            $patients_list .= "<td>{$patient['contact_no']}</td>";

            $patients_list .= "<td>
                                    <div class='action-container'>
                                        <a class='edit-button' href=\"modify-doc-patient.php?user_id={$patient['id']}&page=doc-opd-patient.php\">Edit &nbsp <i class='fa-solid fa-pen-to-square'></i></a>
                                        <a class='medication-button' href=\"add-prescription.php?user_id={$patient['id']}&page=doc-opd-patient.php\">Give Medication</a>
                                    </div>
                                </td>";
			$patients_list .= "</tr>";
		}

 ?>

<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/common.css">
    <link rel="stylesheet" href="./CSS/footer.css">
    <link rel="stylesheet" href="./CSS/ward.css">

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
            <h1 class="page-main-title">OPD Patients Management</h1>
            <hr/>
            <br/><br/>

            <div>

                <table class="detail-table">
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>City</th>
                            <th>Contact No</th>
                            <th id="action-col">Action</th>
                        </tr>
                        
                        <?php echo $patients_list; ?>
                        <?php if(!$patients_list){
                            echo '<td colspan="7" style="text-align:center;">
                                    <i class="fa-sharp fa-solid fa-hourglass"></i>  No Patients Available
                                  </td>';
                                } 
                        ?>
                </table>
            </div>
    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>