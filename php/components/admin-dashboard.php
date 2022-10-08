<?php 
$staff_count = '-';
$doctor_count = '-';
$nurse_count = '-';
$admins_count = '-';


// getting the Counts
$staff_query = "SELECT * FROM staffs WHERE isDeleted != true";
$staffs_result = mysqli_query($connection, $staff_query);

$doctor_query = "SELECT * FROM doctors WHERE isDeleted != true";
$doctors_result = mysqli_query($connection, $doctor_query);

$nurse_query = "SELECT * FROM nurses WHERE isDeleted != true";
$nurses_result = mysqli_query($connection, $nurse_query);

$admin_query = "SELECT * FROM admins WHERE isDeleted != true";
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

$countArray = array($admins_count, $doctor_count,  $nurse_count, $staff_count, 0);



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
            <div class="chart-container">
                <canvas id="myChart" style="max-width:80%;"></canvas>
            </div>

<script>
    var xValues = ["Admins", "Doctors","Nurses","Staff"];
    var yValues = <?php echo json_encode($countArray); ?>;
    var barColors = ["#3fbad9", "#f64879","#31c1a4","#fbbd1b"];
    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
            backgroundColor: barColors,
            data: yValues
            }]
        },
        options: {
            legend: {display: false},
            title: {
            display: true,
            text: "Hospital Management System Users"
            }
        }
    });
</script>
</div>