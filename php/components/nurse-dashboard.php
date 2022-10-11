<?php 
$user_id = $_SESSION['user_id'];
$Active_Patients = '-';
$total_patients = '-';
$nurse_count = '-';


// getting the Counts
$Active_Patients_query = "SELECT * FROM `patients` WHERE isUnderSupervision=true AND isAdmit=false And isDeleted != true";
$Active_Patients_result = mysqli_query($connection, $Active_Patients_query);

$Active_Ward_Patients_query = "SELECT * FROM `patients` WHERE  isUnderSupervision=true AND isAdmit=true And isDeleted != true";
$Active_Ward_Patients_result = mysqli_query($connection, $Active_Ward_Patients_query);

$total_patients_query = "SELECT * FROM `patients` WHERE  isUnderSupervision=true AND isDeleted != true";
$total_patients_result = mysqli_query($connection, $total_patients_query);

$prescriptions_query = "SELECT * FROM prescriptions WHERE isIssued = false";
$prescriptions_result = mysqli_query($connection, $prescriptions_query);




if($Active_Patients_result){
    $Active_Patients = mysqli_num_rows( $Active_Patients_result );
}
if($Active_Ward_Patients_result){
    $Active_Ward_Patients = mysqli_num_rows( $Active_Ward_Patients_result );
}
if($total_patients_result ){
    $total_patients = mysqli_num_rows( $total_patients_result );
}
if($prescriptions_result){
    $prescriptions_count = mysqli_num_rows( $prescriptions_result );
}

    $prescription_list = '';
    $name = '';

	// getting the list
	$query = "SELECT * FROM prescriptions WHERE isIssued=false LIMIT 10";
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

<div>
            <h1 class="dashboard-title">Dashboard </h1>
            <hr/>
            <div>
                <div class="dashboard-card-container">
                    <div class="dashboard-card user-card">
                        <div class="dashboard-card-title"> <i class="fa-solid fa-hand-holding-medical"></i> &nbsp; OPD Patients</div>
                        <div class="dashboard-card-data"><?php echo $Active_Patients ?></div>
                    </div>

                    <div class="dashboard-card staff-card">
                        <div class="dashboard-card-title"> <i class="fa-sharp fa-solid fa-bed-pulse"></i> &nbsp; Ward  Patients</div>
                        <div class="dashboard-card-data"><?php echo $Active_Ward_Patients ?></div>
                    </div>

                    <div class="dashboard-card doctor-card">
                        <div class="dashboard-card-title"> <i class="fa-solid fa-heart"></i> &nbsp;Total Patients</div>
                        <div class="dashboard-card-data"><?php echo $total_patients ?></div>
                    </div>

                    <div class="dashboard-card nurse-card">
                        <div class="dashboard-card-title"> <i class="fa-sharp fa-solid fa-notes-medical"></i> &nbsp; Patient's Prescriptions</div>
                        <div class="dashboard-card-data"><?php echo $prescriptions_count ?></div>
                    </div>
                </div>
            </div>
                
            <div class="doc-patient-mini-tablebox">
                <h1>Top 10 OPD Prescriptions</h1>

                <table class="detail-table">
                        <tr>
                        <tr>
                            <th id="id-col">ID</th>
                            <th>Patient Name</th>
                            <th>Issued Date & Time</th>
                            <th id="action-col">Action</th>
                        </tr>
                        </tr>
                        
                        <?php echo $prescription_list; ?>
                        <?php if(!$prescription_list){
                            echo '<td colspan="7" style="text-align:center;">
                                    <i class="fa-sharp fa-solid fa-hourglass"></i>  No Prescriptions Available Please Check Again Later
                                  </td>';
                                } 
                        ?>
                </table>
            </div>
</div>