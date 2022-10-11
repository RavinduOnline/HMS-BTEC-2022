<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'nurse' != $_SESSION['access']) {
		header('Location: login.php');
	}

    $errors = array();

    $prescription_id = '';
    $docID = '';
    $doctor_name = '';
    $date = '';
    $name = '';
    $description = '';
    $prescription = '';


    if (isset($_GET['prescription_id']) && isset($_GET['name']) && isset($_GET['date']) && isset($_GET['doc'])) {

        $prescription_id = $_GET['prescription_id'];
        $docID = $_GET['doc'];
        $date = $_GET['date'];
        $name = $_GET['name'];

        //find doctor name
        $docID = mysqli_real_escape_string($connection, $_GET['doc']);
		$query = "SELECT * FROM doctors WHERE id = {$docID} LIMIT 1";
		$result_set = mysqli_query($connection, $query);

            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    // user found
                    $result = mysqli_fetch_assoc($result_set);
                    $doctor_name = $result['name'];
                } else {
                    // doctor not found
                    header('Location:prescription.php?err=doctor_not_found');	
                }
            }


    
        //get prescription

        $prescription_id = mysqli_real_escape_string($connection, $_GET['prescription_id']);
		$query = "SELECT * FROM prescriptions WHERE id = {$prescription_id} LIMIT 1";
		$result_set = mysqli_query($connection, $query);

            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    // prescription found
                    $result = mysqli_fetch_assoc($result_set);
                    $description = $result['description'];
                } else {
                    // prescription not found
                    header('Location:prescription.php?err=description_not_found');	
                }
            }

        
        
        

    }
    else{
        // query unsuccessful
			header('Location: prescription.php');
    }


    // if (isset($_POST['submit']) || isset($_GET['ward_no'])) {

    //     $wardNo = $_POST['ward_no'];
    //     if(!isset($_POST['submit'])){
    //         $wardNo = $_GET['ward_no'];
    //     }else{
    //          // checking required fields
    //             $req_fields = array('ward_no');
    //             $errors = array_merge($errors, check_req_fields($req_fields));

    //             // checking max length
    //             $max_len_fields = array('ward_no' => 2);
    //             $errors = array_merge($errors, check_max_len($max_len_fields));
    //     }

       

    //     if (empty($errors)) {
    //             $wardNo = mysqli_real_escape_string($connection, $_POST['ward_no']);
    //             if(!isset($_POST['submit'])){
    //                 $wardNo = mysqli_real_escape_string($connection, $_GET['ward_no']);
    //             }
                
    //             // getting the list of beds
    //             $query = "SELECT * FROM beds WHERE wardNo = {$wardNo} AND isDeleted != true ORDER BY bedNo";
    //             $beds = mysqli_query($connection, $query);
               


    //             if(mysqli_num_rows($beds) >= 1){
    //                 $total_bed_count = mysqli_num_rows( $beds );

    //                 while ($bed = mysqli_fetch_assoc($beds)) {
    //                     $bed_list .= "<tr>";
    //                     $bed_list .= "<td  id='text-centre'>{$bed['bedNo']}</td>";

    //                         $bedNo = $bed['bedNo'];
    //                         if($bedNo){
    //                             $getPatientQuery = "SELECT first_name, last_name FROM `patients` WHERE  isDeleted = false AND bedID = {$bedNo} AND wardNo = {$wardNo} LIMIT 1 ";
    //                             $getPatient_result = mysqli_query($connection, $getPatientQuery);
    //                             $getPatient = mysqli_fetch_assoc($getPatient_result);

    //                             if($getPatient){
    //                                 $bed_list .= "<td>{$getPatient['first_name']} {$getPatient['last_name']}</td>";
    //                                 $filled_bed_count++;
    //                             }
    //                             else{
    //                                 $bed_list .= "<td>There is no patient</td>";
    //                             }

    //                         }

    //                         if($_SESSION['access'] == 'admin'){
    //                             $bed_list .= "<td>
    //                                                 <div class='action-container'>
    //                                                     <a class='delete-button' href=\"delete-bed.php?bed_id={$bed['id']}&ward_id={$wardNo}\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
    //                                                 </div>
    //                                             </td>";
    //                         }

    //                     $bed_list .= "</tr>";
    //                 }

    //                 // getting the head doctor name
    //                 $query = "SELECT headDocID FROM wards WHERE id = {$wardNo} LIMIT 1";
    //                 $wardDocResult = mysqli_query($connection, $query);
    //                 verify_query($wardDocResult);
    //                 $wardDoc = mysqli_fetch_assoc($wardDocResult);
    //                 $docID = $wardDoc['headDocID'];

    //                 $query = "SELECT * FROM doctors WHERE id = {$docID} LIMIT 1";
    //                 $DocResult = mysqli_query($connection, $query);
    //                 verify_query($DocResult);
    //                 $Doc = mysqli_fetch_assoc($DocResult);
    //                 $HeadDoc = $Doc['name'];
                
    //                 $haveResults = true;
    //             }
    //             else {
	// 				// Ward Number invalid
	// 				$errors[] = "You'r entered ward number is invalid";
	// 			}
    //     }    
                

    // }

 ?>

<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/common.css">
    <link rel="stylesheet" href="./CSS/footer.css">
    <link rel="stylesheet" href="./CSS/ward.css">
    <link rel="stylesheet" href="./CSS/dashboard.css">

    <!--load icon styles -->
    <script src="https://kit.fontawesome.com/853b48ffc0.js" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Medicines - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Prescription</h1>
            <hr/>

            <div>
                    <div class="ward-bed-top-details">
                            <h3>Patient Name - <?php echo "$name" ?></h3>
                            <h3>Issued Doctor  - <?php echo "$doctor_name" ?></h3>
                            <h3>Issued Date & Time -  <?php echo "$date" ?></h3>
                    </div>
                        <hr/>

                    <div class="ward-bed-top-details">
                            <?php
                            if($description){ 
                                print_r(str_replace("-"," - ",str_replace(",","<br/><br/>",$description,$i)));
                                echo "<br>";
                                }
                            ?>
                    </div>

                    <div class="prescription-button-box">
                        <a href="prescription.php" id="prescription-back">Back</a>
                        <a href="prescription-completed.php?prescription_id=<?php echo $prescription_id?>" id="prescription-complete">Completed</a>
                    </div>
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>