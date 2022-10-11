<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}

    $wards_list = '';
    $wardNo = '';
    $ward_result = '';
    $ward_count = '';

	// getting the list of wards
	$query = "SELECT * FROM wards ORDER BY create_datetime DESC";
	$wards = mysqli_query($connection, $query);


	verify_query($wards);
		while ($ward = mysqli_fetch_assoc($wards)) {
			$wards_list .= "<tr>";
			$wards_list .= "<td  id='text-centre'>{$ward['id']}</td>";

                $wardNo = $ward['id'];
                if($wardNo){
                    
                    $getBedCountQuery = "SELECT bedNo FROM beds WHERE wardNo = {$wardNo} AND isDeleted != true";
                    $ward_result = mysqli_query($connection, $getBedCountQuery);
                    $ward_count = mysqli_num_rows( $ward_result );

                    $getFilledBedCountQuery = "SELECT * FROM patients p WHERE wardNo = {$wardNo} AND isAdmit = true AND isDeleted != true";
                    $FilledBedCount_result = mysqli_query($connection, $getFilledBedCountQuery);
                    $FilledBed_count = mysqli_num_rows( $FilledBedCount_result );

                    $AvailableBedCount = $ward_count - $FilledBed_count;

                    $wards_list .= "<td  id='text-centre'>{$ward_count}</td>";
                    $wards_list .= "<td  id='text-centre'>{$AvailableBedCount}</td>";
                    $wards_list .= "<td  id='text-centre'>{$FilledBed_count}</td>";

                }

                $headDocID = $ward['headDocID'];
                if($headDocID){
                    $getHeadDocQuery = "SELECT * FROM doctors WHERE id = {$headDocID} LIMIT 1";
                    $getHeadDoc = mysqli_query($connection, $getHeadDocQuery);
                    $HeadDoc = mysqli_fetch_assoc($getHeadDoc);

                    $wards_list .= "<td>{$HeadDoc['name']}</td>";
                }

            if($_SESSION['access'] == 'admin'){
                $wards_list .= "<td>
                                    <div class='action-container'>
                                        <a class='delete-button' href=\"delete-user.php?user_id={$ward['id']}\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
                                    </div>
                                </td>";
            }
			$wards_list .= "</tr>";
		}



        $total_bed_count = '-';
        $available_bed_count = '-';
        $filled_bed_count = '-';
        $total_wards_count = '-';


        // getting the Counts
        $total_bed_query = "SELECT * FROM beds WHERE  isDeleted != true";
        $total_bed_result = mysqli_query($connection, $total_bed_query);

        $filled_bed_query = "SELECT * FROM patients WHERE isAdmit = true AND isDeleted != true";
        $filled_bed_result = mysqli_query($connection, $filled_bed_query);

        $total_wards_query = "SELECT * FROM wards WHERE isDeleted != true";
        $total_wards_result = mysqli_query($connection, $total_wards_query);


        if($total_bed_result){
            $total_bed_count = mysqli_num_rows( $total_bed_result );
        }
        if($filled_bed_result){
            $filled_bed_count = mysqli_num_rows( $filled_bed_result );
        }
        if($total_bed_result && $filled_bed_result ){
            $available_bed_count = $total_bed_count -  $filled_bed_count;
        }
        if($total_wards_result){
            $total_wards_count = mysqli_num_rows( $total_wards_result );
        }


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
    <title>Manage Ward - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Ward Management</h1>
            <hr/>

            <div>

            <?php 
                    if($_SESSION['access'] == 'admin'){
                         include './php/components/ward-management-count.php';
                       }
              ?>

                <div class="ward-dashboard-button-container">
                                    <div>
                                        <a href="ward-bed.php" class="left-button-ward"><i class="fa-solid fa-bed"></i>  &nbsp; View Ward Bed Details</a>
                                        <?php 
                                            if( $_SESSION['access'] != 'nurse'){
                                                echo '<a href="ward-staff.php" class="left-button-ward"><i class="fa-solid fa-clipboard-user"></i>  &nbsp; View Ward Staff Details</a>';
                                            }
                                        ?>
                                    </div>
                             <?php 
                                  if($_SESSION['access'] == 'admin'){
                                    echo '<div><a href="add-ward.php">Add New Ward &nbsp<i class="fa-solid fa-plus"></i></a></div>';
                                  }
                            ?>
                </div>
                
                <table class="detail-table">
                        <tr>
                            <th id="id-col">Ward No</th>
                            <th>Total Beds</th>
                            <th>Available Beds</th>
                            <th>Filled Beds</th>
                            <th>Head Doctor</th>
                            <?php 
                                  if($_SESSION['access'] == 'admin'){
                                    echo '<th id="action-col">Action</th>';
                                  }
                            ?>
                        </tr>
                        
                        <?php echo $wards_list; ?>
                </table>
            </div>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>