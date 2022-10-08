<?php 
$user_id = $_SESSION['user_id'];
$Active_Patients = '-';
$total_patients = '-';
$nurse_count = '-';


// getting the Counts
$Active_Patients_query = "SELECT * FROM `patients` WHERE supervisingDocID=$user_id AND isUnderSupervision=true AND isAdmit=false And isDeleted != true";
$Active_Patients_result = mysqli_query($connection, $Active_Patients_query);

$Active_Ward_Patients_query = "SELECT * FROM `patients` WHERE supervisingDocID=$user_id AND isUnderSupervision=true AND isAdmit=true And isDeleted != true";
$Active_Ward_Patients_result = mysqli_query($connection, $Active_Ward_Patients_query);

$total_patients_query = "SELECT * FROM `patients` WHERE supervisingDocID=$user_id And isDeleted != true";
$total_patients_result = mysqli_query($connection, $total_patients_query);

$nurse_query = "SELECT * FROM nurses WHERE isDeleted != true";
$nurses_result = mysqli_query($connection, $nurse_query);




if($Active_Patients_result){
    $Active_Patients = mysqli_num_rows( $Active_Patients_result );
}
if($Active_Ward_Patients_result){
    $Active_Ward_Patients = mysqli_num_rows( $Active_Ward_Patients_result );
}
if($total_patients_result ){
    $total_patients = mysqli_num_rows( $total_patients_result );
}
if($nurses_result){
    $nurse_count = mysqli_num_rows( $nurses_result );
}

$patients_list = '';

// getting the list of patients
$query = "SELECT * FROM patients WHERE supervisingDocID=$user_id AND isUnderSupervision=true AND isAdmit=false AND isDeleted != true  LIMIT 10";
$patients = mysqli_query($connection, $query);

verify_query($patients);
    $AdmitStatus = '';
    $no = 0;
    while ($patient = mysqli_fetch_assoc($patients)) {
        $no ++;
        $patients_list .= "<tr>";
        $patients_list .= "<td>{$no}</td>";
        $patients_list .= "<td>{$patient['id']}</td>";
        $patients_list .= "<td>{$patient['first_name']} {$patient['last_name']}</td>";
        $patients_list .= "<td>{$patient['nic']}</td>";
        $patients_list .= "<td>{$patient['city']}</td>";
        if($patient['isAdmit']){
            $AdmitStatus = 'Admitted';
        }
        else{
            $AdmitStatus = 'Not Admitted';
        }
        $patients_list .= "<td>{$AdmitStatus}</td>";
        $patients_list .= "<td>
                             <div class='action-container'>
                                <a class='medication-button' href=\"modify-user.php?user_id={$patient['id']}\">Give Medication &nbsp <i class='fa-solid fa-kit-medical'></i></a>
                              </div>
                          </td>";
        $patients_list .= "</tr>";
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
                        <div class="dashboard-card-data"><?php echo $nurse_count ?></div>
                    </div>
                </div>
            </div>
                
            <div class="doc-patient-mini-tablebox">
                <h1>Top 10 OPD Patients</h1>

                <table class="detail-table">
                        <tr>
                            <th id="id-col">No</th>
                            <th id="id-col">ID</th>
                            <th>Name</th>
                            <th>NIC</th>
                            <th>City</th>
                            <th>Patient Status</th>
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