<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id']) || 'admin' != $_SESSION['access']) {
		header('Location: login.php');
	}

 

    $doctors_list = '';
    $errors = array();
    $firstName = '';
    $LastName = '';
    $nic = '';
    $contactNumber = '';
    $houseNumber = '';
    $streetName = '';
    $city = '';
    $CustodianName = '';
    $CustodianNumber = '';
    $supervisingDoctor = '';
    $isAdmit = 0;
    $ward_no = 0;
    $bed_no = '';
    $user_id ='';
    $supervisingDoctorName ='';
    $Supervising_Status = '';
    $SupervisingStatusText = '';




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
    

        if (isset($_GET['user_id'])) {
            // getting the user information
            $user_id = mysqli_real_escape_string($connection, $_GET['user_id']);
            $query = "SELECT p.* , d.name  FROM patients p , doctors d WHERE p.id = {$user_id} AND p.supervisingDocID = d.id  LIMIT 1 ";
            $result_set = mysqli_query($connection, $query);
    
            if ($result_set) {
                if (mysqli_num_rows($result_set) == 1) {
                    // user found
                    $result = mysqli_fetch_assoc($result_set);

                    $user_id = $result['id'];
                    $firstName = $result['first_name'];
                    $LastName = $result['last_name'];
                    $nic = $result['nic'];
                    $contactNumber = $result['contact_no'];
                    $houseNumber = $result['house_no'];
                    $streetName = $result['street_name'];
                    $city = $result['city'];
                    $CustodianName = $result['custodians_name'];
                    $CustodianNumber = $result['custodians_contact_no'];
                    $supervisingDoctor = $result['supervisingDocID'];
                    $supervisingDoctorName = $result['name'];
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
                    header('Location:patient.php?err=user_not_found');	
                }
            } else {
                // query unsuccessful
                header('Location: patient.php?err=query_failed');
            }
        }


	if (isset($_POST['submit'])) {

        $user_id = $_POST['user_id'];
        $firstName = $_POST['first_Name'];
        $LastName = $_POST['last_Name'];
        $nic = $_POST['nic'];
        $contactNumber = $_POST['contact_Number'];
        $houseNumber = $_POST['house_No'];
        $streetName = $_POST['street_Name'];
        $city = $_POST['city'];
        $CustodianName = $_POST['custodians_Name'];
        $CustodianNumber = $_POST['custodian_Contact_Number'];
        $supervisingDoctor = $_POST['supervising_Doctor'];
        $supervisingDoctorName = $_POST['supervisingDoctorName'];
        $isAdmit = $_POST['patient_Status'];
        $ward_no =$_POST['wardNO'];
        $bed_no = $_POST['bed'];
        $Supervising_Status = $_POST['Supervising_Status'];
        $SupervisingStatusText = $_POST['SupervisingStatusText'];

        // checking patient is admit
        if($isAdmit){
            // checking required fields
            $req_fields = array('wardNO', 'bed');
            $errors = array_merge($errors, check_req_fields($req_fields));
        }

		// checking required fields
		$req_fields = array('first_Name', 'last_Name', 'nic', 'contact_Number', 'house_No', 'street_Name', 'city', 'supervising_Doctor');
		$errors = array_merge($errors, check_req_fields($req_fields));

        if( !ctype_digit($contactNumber) || (!ctype_digit($CustodianNumber) && $CustodianNumber)){
            $errors[] = 'Only number(s) can be entered in the contact number fields';
        }
        
       

        // checking max length
		$max_len_fields = array('first_Name' => 100, 'last_Name' =>100, 'nic' => 12, 'contact_Number' => 10,'house_No' => 20, 'street_Name' => 100, 'city' => 100, 'patient_Status' => 1, 'CustodianName' => 100,'CustodianNumber' => 10 );
        $errors = array_merge($errors, check_max_len($max_len_fields));

        // checking if NIC  already exists
		$nic = mysqli_real_escape_string($connection, $_POST['nic']);
		$query = "SELECT * FROM patients WHERE nic = '{$nic}' AND id != {$user_id}  LIMIT 1";

		$result_set = mysqli_query($connection, $query);

        if ($result_set) {
			if (mysqli_num_rows($result_set) == 1) {
				$errors[] = 'NIC already exists';
			}
		}


        if (empty($errors)) {
			// no errors found... adding new record
            $user_id =mysqli_real_escape_string($connection,$_POST['user_id']);
            $firstName = mysqli_real_escape_string($connection, $_POST['first_Name']);
            $LastName = mysqli_real_escape_string($connection, $_POST['last_Name']);
            $nic = mysqli_real_escape_string($connection, $_POST['nic']);
            $contactNumber = mysqli_real_escape_string($connection, $_POST['contact_Number']);
            $houseNumber = mysqli_real_escape_string($connection, $_POST['house_No']);
            $streetName = mysqli_real_escape_string($connection, $_POST['street_Name']);
            $city = mysqli_real_escape_string($connection, $_POST['city']);
            $CustodianName = mysqli_real_escape_string($connection, $_POST['custodians_Name']);
            $CustodianNumber = mysqli_real_escape_string($connection, $_POST['custodian_Contact_Number']);
            $supervisingDoctor = mysqli_real_escape_string($connection, $_POST['supervising_Doctor']);
            $isAdmit = mysqli_real_escape_string($connection, $_POST['patient_Status']);
            $ward_no = mysqli_real_escape_string($connection, $_POST['wardNO']);
            $bed_no = mysqli_real_escape_string($connection, $_POST['bed']);
            $Supervising_Status =  mysqli_real_escape_string($connection, $_POST['Supervising_Status']);

            if(empty(trim($CustodianName))){
                $CustodianName = null;
            }
            if(empty(trim($CustodianNumber))){
                $CustodianNumber = null;
            }

            if($isAdmit == 1){
                $query =  "UPDATE  `patients` SET `first_name` ='{$firstName}' , `last_name` = '{$LastName}', `nic` = '{$nic}', `house_no` = '{$houseNumber}', `street_name` = '{$streetName}', `city` = '{$city}', `contact_no` =  $contactNumber, `custodians_name` = '{$CustodianName}', `custodians_contact_no` = '{$CustodianNumber}', `isUnderSupervision` =  $supervisingDoctor, `isAdmit` = $isAdmit, `isUnderSupervision`= $Supervising_Status , `wardNo` = $ward_no, `bedID` =  $bed_no WHERE `id` ={$user_id} ";
            }
            if($isAdmit == 0){
                $query = "UPDATE  `patients` SET `first_name` ='{$firstName}' , `last_name` = '{$LastName}', `nic` = '{$nic}', `house_no` = '{$houseNumber}', `street_name` = '{$streetName}', `city` = '{$city}', `contact_no` = $contactNumber, `custodians_name` = '{$CustodianName}', `custodians_contact_no` = '{$CustodianNumber}', `isUnderSupervision` =  $supervisingDoctor, `isAdmit` = $isAdmit , `isUnderSupervision`= $Supervising_Status , `wardNo` = null, `bedID` =  null WHERE `id` = {$user_id}";
            }

			
			$result = mysqli_query($connection, $query);

			if ($result) {
				// query successful... redirecting to doctor page
				header('Location: patient.php?patient_added=true');
			} else {
				$errors[] = 'Failed to add the new record.';
			}


		}

	}




    if (isset($_POST['submit_bed'])) {

        $bed_list = '';
        $bedID = '';

        $user_id = $_POST['user_id'];
        $firstName = $_POST['first_Name'];
        $LastName = $_POST['last_Name'];
        $nic = $_POST['nic'];
        $contactNumber = $_POST['contact_Number'];
        $houseNumber = $_POST['house_No'];
        $streetName = $_POST['street_Name'];
        $city = $_POST['city'];
        $CustodianName = $_POST['custodians_Name'];
        $CustodianNumber = $_POST['custodian_Contact_Number'];
        $supervisingDoctor = $_POST['supervising_Doctor'];
        $supervisingDoctorName = $_POST['supervisingDoctorName'];
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
    <title>Add New Patient - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">&nbsp Modify Patient</h1>
            <hr/>

            <div>
                <div class="addpage-top-container">
                    <a href="patient.php" class="back-button"><i class="fa-solid fa-chevron-left"></i>&nbsp Back to Patient List</a>
                </div>

                <div class="form-container form-container-Patient">
                    <form action="modify-patient.php" method="post" class="form-box">
                        <input <?php echo 'value="' . $user_id . '"'; ?> type="hidden" name="user_id">
                        <div class="input-container">
                           <div>
                                <label>First Name:</label>
                                    <input type="text" name="first_Name" placeholder="Entre First  Name" maxlength="100"   <?php echo 'value="' . $firstName . '"'; ?>required>
                           </div>

                           <div>
                                <label>Last Name:</label>
                                    <input type="text" name="last_Name" placeholder="Entre Last Name" maxlength="100"   <?php echo 'value="' . $LastName . '"'; ?>required>
                           </div>
                        </div>

                        <div class="input-container">
                           <div>
                                    <label>NIC No:</label>
                                    <input type="text" name="nic" placeholder="Entre NIC Number"   <?php echo 'value="' . $nic . '"'; ?> >
                           </div>

                           <div>
                                <label>Contact Number:</label>
                                <input type="tel" name="contact_Number" placeholder="Entre Contact Number"   <?php echo 'value="' . $contactNumber . '"'; ?> >
                           </div>
                        </div>

                        <div class="input-container">
                           <div>
                                    <label>House No:</label>
                                    <input type="text" name="house_No" placeholder="Entre House Number" maxlength="20"  <?php echo 'value="' . $houseNumber . '"'; ?> required>
                           </div>

                           <div>
                                    <label>Street Name:</label>
                                    <input type="text" name="street_Name" placeholder="Entre Street Name"   <?php echo 'value="' . $streetName . '"'; ?> >
                           </div>
                        </div>

                        <div>
                            <label>City:</label>
                            <br/>
                            <input type="text" name="city" placeholder="Entre City"   <?php echo 'value="' . $city . '"'; ?> required>
                        </div>

                        <div>
                            <label>Custodian's Name:</label>
                            <br/>
                            <input type="text" name="custodians_Name" placeholder="Entre Custodian's Name"   <?php echo 'value="' . $CustodianName . '"'; ?>>
                        </div>

                        <div>
                            <label>Custodian's Contact Number:</label>
                            <br/>
                            <input type="tel" name="custodian_Contact_Number" placeholder="Entre Custodian's Contact Number"   <?php echo 'value="' . $CustodianNumber . '"'; ?>>
                        </div>

                        <div>
                                                <input <?php echo 'value="' . $supervisingDoctorName . '"'; ?> type="hidden" name="supervisingDoctorName">
                            <label >Supervising Doctor:</label>
                            <br/>
                                <select name="supervising_Doctor"  <?php echo 'value="' . $supervisingDoctor . '"'; ?>>
                                    <option value="<?php echo $supervisingDoctor; ?>" selected hidden><?php echo"$supervisingDoctorName"; ?></option>
                                    <?php echo $doctors_list; ?>
                                </select>
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