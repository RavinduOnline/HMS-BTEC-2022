<?php 
$user_id = $_SESSION['user_id'];
$Active_Patients = '-';
$total_patients = '-';
$nurse_count = '-';


// getting the Counts
$Active_Patients_query = "SELECT * FROM `patients` WHERE  isUnderSupervision=true AND isAdmit=false And isDeleted != true";
$Active_Patients_result = mysqli_query($connection, $Active_Patients_query);

$Active_Ward_Patients_query = "SELECT * FROM `patients` WHERE  isUnderSupervision=true AND isAdmit=true And isDeleted != true";
$Active_Ward_Patients_result = mysqli_query($connection, $Active_Ward_Patients_query);

$total_patients_query = "SELECT * FROM `patients` WHERE  isUnderSupervision=true AND isDeleted != true";
$total_patients_result = mysqli_query($connection, $total_patients_query);

$prescriptions_query = "SELECT * FROM prescriptions";
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
                        <div class="dashboard-card-title"> <i class="fa-sharp fa-solid fa-notes-medical"></i> &nbsp; Patient's Reports</div>
                        <div class="dashboard-card-data"><?php echo $prescriptions_count ?></div>
                    </div>
                </div>
            </div>
                
            
</div>