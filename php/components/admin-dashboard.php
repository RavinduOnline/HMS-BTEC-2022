<?php 
$staff_count = '-';
$doctor_count = '-';
$nurse_count = '-';
$admins_count = '-';


// getting the Counts
$staff_query = "SELECT * FROM staffs";
$staffs_result = mysqli_query($connection, $staff_query);

$doctor_query = "SELECT * FROM doctors";
$doctors_result = mysqli_query($connection, $doctor_query);

$nurse_query = "SELECT * FROM nurses";
$nurses_result = mysqli_query($connection, $nurse_query);

$admin_query = "SELECT * FROM admins";
$admins_result = mysqli_query($connection, $admin_query);


if($staffs_result){
    $staff_count = mysqli_num_rows( $staffs_result );
}
if($doctors_result ){
    $doctor_count = mysqli_num_rows( $doctors_result );
}
if($nurses_result){
    $nurse_count = mysqli_num_rows( $nurses_result );
}
if($admins_result){
    $admins_count = mysqli_num_rows( $admins_result );
}





?>

<div>
            <h1 class="dashboard-title">Dashboard </h1>
            <hr/>
            <div>
                <div class="dashboard-card-container">
                    <div class="dashboard-card user-card">
                        <div class="dashboard-card-title"> <i class="fa-sharp fa-solid fa-users"></i> &nbsp; Admins</div>
                        <div class="dashboard-card-data"><?php echo $admins_count ?></div>
                    </div>

                    <div class="dashboard-card doctor-card">
                        <div class="dashboard-card-title"> <i class="fa-sharp fa-solid fa-user-doctor"></i> &nbsp; Doctors</div>
                        <div class="dashboard-card-data"><?php echo $doctor_count ?></div>
                    </div>

                    <div class="dashboard-card nurse-card">
                        <div class="dashboard-card-title"> <i class="fa-solid fa-user-nurse"></i> &nbsp; Nurses</div>
                        <div class="dashboard-card-data"><?php echo $nurse_count ?></div>
                    </div>

                    <div class="dashboard-card staff-card">
                        <div class="dashboard-card-title"> <i class="fa-solid fa-user-tie"></i> &nbsp; Other Staff</div>
                        <div class="dashboard-card-data"><?php echo $staff_count ?></div>
                    </div>
                </div>
            </div>
</div>