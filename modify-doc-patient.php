<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'staff' == $_SESSION['access']) {
		header('Location: login.php');
	}

 

    $doctors_list = '';
    $errors = array();
    $supervisingDoctor = '';
    $isAdmit = 0;
    $ward_no = 0;
    $bed_no = '';
    $user_id ='';
    $supervisingDoctorName ='';
    $Supervising_Status = '';
    $SupervisingStatusText = '';
    $page = '';
    $name = '';





	// getting the list of doctors
	$query = "SELECT * FROM doctors WHERE isDeleted != true";
	$doctors = mysqli_query($connection, $query);


	verify_query($doctors);
		while ($doctor = mysqli_fetch_assoc($doctors)) {
			$doctors_list .= "<option value='{$doctor['id']}'>{$doctor['name']} - {$doctor['type']} </option>";
	}
    
        $isAdmit = $_POST['patient_Status'];
        $ward_list = "";

        // getting the list of wards
        $query = "SELECT * FROM wards WHERE isDeleted != true";
        $wards = mysqli_query($connection, $query);

        verify_query($wards);
            while ($ward = mysqli_fetch_assoc($wards)) {
                $ward_list .= "<option value='{$ward['id']}'>{$ward['id']}</option>";
        }
    

        if (isset($_GET['user_id']) && isset($_GET['page'])) {
            // getting the user information
            $page = $_GET['page'];
            $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
            $query = "SELECT p.* , d.name  FROM patients p , doctors d WHERE p.id = {$user_id} AND p.supervisingDocID = d.id  LIMIT 1 ";
            $result_set = mysqli_query($connection, $query);
    
            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    // user found
                    $result = mysqli_fetch_assoc($result_set);

                    $name = "{$result['first_name']} {$result['last_name']}";
                    $user_id = $result['id'];
                    $isAdmit = $result['isAdmit'];
                    $ward_no = $result['wardNo'];
                    $bed_no = $result['bedID'];
                    $Supervising_Status = $result['isUnderSupervision'];

                    if($isAdmit){
                        $isAdmitStatus = 'Admitted';
                    }
                    else{
                        $isAdmitStatus = 'Not Admitted';
                    }

                    if($Supervising_Status){
                        $SupervisingStatusText = "Need";
                    }
                    else{
                        $SupervisingStatusText = "No Need";
                    }

                } else {
                    // user not found
                    header("Location:$page?err=user_not_found");	
                }
            } else {
                // query unsuccessful
                header("Location: $page?err=query_failed");
            }
        }


	if (isset($_POST['submit'])) {

        $user_id = $_POST['user_id'];
        $isAdmit = $_POST['patient_Status'];
        $ward_no =$_POST['wardNO'];
        $bed_no = $_POST['bed'];
        $Supervising_Status = $_POST['Supervising_Status'];
        $SupervisingStatusText = $_POST['SupervisingStatusText'];
        $name = $_POST['name'];
        $page = $_POST['page'];

        // checking patient is admit
        if($isAdmit){
            // checking required fields
            $req_fields = array('wardNO', 'bed');
            $errors = array_merge($errors, check_req_fields($req_fields));
        }
        
       

        // checking max length
		$max_len_fields = array('patient_Status' => 1 );
        $errors = array_merge($errors, check_max_len($max_len_fields));

        if (empty($errors)) {
			// no errors found... adding new record
            $user_id =mysqli_real_escape_string($connection,$_POST['user_id']);
            $isAdmit = mysqli_real_escape_string($connection, $_POST['patient_Status']);
            $ward_no = mysqli_real_escape_string($connection, $_POST['wardNO']);
            $bed_no = mysqli_real_escape_string($connection, $_POST['bed']);
            $Supervising_Status =  mysqli_real_escape_string($connection, $_POST['Supervising_Status']);



            if($isAdmit == 1){
                $query =  "UPDATE  `patients` SET `isAdmit` = $isAdmit, `isUnderSupervision`= $Supervising_Status , `wardNo` = $ward_no, `bedID` =  $bed_no WHERE `id` ={$user_id} ";
            }
            if($isAdmit == 0){
                $query = "UPDATE  `patients` SET `isAdmit` = $isAdmit , `isUnderSupervision`= $Supervising_Status , `wardNo` = null, `bedID` =  null WHERE `id` = {$user_id}";
            }

			
			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header("Location: $page?patient_added=true");
			} else {
				$errors[] = 'Failed to add the new record.';
			}


		}

	}




    if (isset($_POST['submit_bed'])) {

        $bed_list = '';
        $bedID = '';

        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $page = $_POST['page'];
        $isAdmit = $_POST['patient_Status'];
        $ward_no =$_POST['wardNO'];
        $Supervising_Status = $_POST['Supervising_Status'];
        $SupervisingStatusText = $_POST['SupervisingStatusText'];


        // checking required fields
        if(empty(trim($ward_no))){
            $errors[] = "Ward number is required to get a bed numbers";
        }

        if (empty($errors)) {
            $ward_no = mysqli_real_escape_string($connection,$_POST['wardNO']);
            // getting the list of wards
            $query = "SELECT bedNo FROM beds  WHERE wardNo = {$ward_no} AND isDeleted = false";
            $beds = mysqli_query($connection, $query);
    
            verify_query($beds);
                while ($bed = mysqli_fetch_assoc($beds)) {

                        $bedID = $bed['bedNo'];
                        $query = "SELECT * FROM `patients` WHERE wardNo = {$ward_no} AND bedID = {$bedID} AND isAdmit = true AND isDeleted = false";
                        $bed_result = mysqli_query($connection, $query);
                        $has_patient = mysqli_num_rows($bed_result);

                        if(!$has_patient){
                        $bed_list .= "<option value='{$bed['bedNo']}'>{$bed['bedNo']}</option>";
                        }
                }
    
        }

    }



?>


<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/sidebar.css">
    <link rel="stylesheet" href="./CSS/common.css">
    <link rel="stylesheet" href="./CSS/footer.css">
    <link rel="stylesheet" href="./CSS/patient.css">


    <!--load icon styles -->
    <script src="https://kit.fontawesome.com/853b48ffc0.js" crossorigin="anonymous"></script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Patient - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Modify Patient</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="<?php echo $page ; ?>" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Patient List</a>
                </div>

                <div class="form-container form-container-Patient">
                    <form action="modify-doc-patient.php" method="post" class="form-box">

                        <input <?php echo 'value="' . $user_id . '"'; ?> type="hidden" name="user_id">
                        <input <?php echo 'value="' . $page . '"'; ?> type="hidden" name="page">

                        <div>
                            <label >Name:</label>
                            <br/>
                            <input <?php echo 'value="' . $name . '"'; ?> type="text" name="name" readonly>
                        </div>

                        <div>
                             <input <?php echo 'value="' . $SupervisingStatusText . '"'; ?> type="hidden" name="SupervisingStatusText">
                            <label >Supervising Status:</label>
                            <br/>
                                <select name="Supervising_Status"  >
                                    <option value="<?php echo $Supervising_Status; ?>" selected hidden><?php echo"$SupervisingStatusText"; ?></option>
                                    <option value="0" >No Need</option>
                                    <option value="1">Need</option>
                                </select>
                        </div>
                        
                        <div>
                            <label>Patient Status:</label>
                            <br/>
                                <select name="patient_Status" >
                                         <option value="<?php echo $isAdmit; ?>" selected hidden><?php echo"$isAdmitStatus";  echo $isAdmit;?></option>
                                         <option value='1'>Admit</option>
                                         <option value='0'>No Need to Admit</option>    
                                </select>
                        </div>


                        <div>
                                <label>Ward No: <span id="form-notice">(Only for admitted patients)</span></label>
                                    <br/>
                                    <select name="wardNO"  >
                                        <option value="<?php echo $ward_no; ?>" selected hidden><?php echo"$ward_no"; ?></option>
                                        <?php echo "$ward_list" ; ?>
                                        <option value="">Ward number is not relevant</option>                                   
                                    </select>
                               
                        </div>

                        <div>
                                    <label>Bed No: <span id="form-notice">(Only for admitted patients)</span></label>
                                    <br/>
                                        <select name="bed"  >
                                        <?php 
                                            if($bed_no){
                                                echo "<option value='{$bed_no}'  selected hidden>{$bed_no}</option>
                                                <option value=''>Bed number is not relevant</option>"; 
                                            }
                                            else{
                                                echo " <option value='' selected hidden>Select Bed</option>";
                                            }
                                        ?>
                                        <?php echo ".$bed_list."; ?>
                                        </select>
                                </div>
                                <?php 

                                        echo " 
                                            <div action='modify-patient.php?user_id=<?php echo $user_id?>' method='post'   class='add-ward-button-container'>
                                                <button type='submit' name='submit_bed'>Get Beds (If it's a admitted patient)</button>
                                            </div>
                                            ";

                                ?>  
                              
                        <br/><br/>
                        <div class="submit-button-container">
                            <button type="submit" name="submit">Modify</button>
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