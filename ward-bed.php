<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}

    $errors = array();

    $bed_list = '';
    $wardNo = '';
    $bed_result = '';
    $bed_count = '';
    $filled_bed_count = 0;
    $HeadDoc = '';
    $docID = '';
    $haveResults= false;

    if (isset($_GET['err'])) {
        if($_GET['err'] == "cannot_delete_bed_has_patients"){
            $error = "It cannot be deleted because there is a patient in the bed";
        }
    }


    if (isset($_POST['submit']) || isset($_GET['ward_no'])) {

        $wardNo = $_POST['ward_no'];
        if(!isset($_POST['submit'])){
            $wardNo = $_GET['ward_no'];
        }else{
             // checking required fields
                $req_fields = array('ward_no');
                $errors = array_merge($errors, check_req_fields($req_fields));

                // checking max length
                $max_len_fields = array('ward_no' => 2);
                $errors = array_merge($errors, check_max_len($max_len_fields));
        }

       

        if (empty($errors)) {
                $wardNo = mysqli_real_escape_string($connection, $_POST['ward_no']);
                if(!isset($_POST['submit'])){
                    $wardNo = mysqli_real_escape_string($connection, $_GET['ward_no']);
                }
                
                // getting the list of beds
                $query = "SELECT * FROM beds WHERE wardNo = {$wardNo} AND isDeleted != true ORDER BY bedNo";
                $beds = mysqli_query($connection, $query);
               


                if(mysqli_num_rows($beds) >= 1){
                    $total_bed_count = mysqli_num_rows( $beds );

                    while ($bed = mysqli_fetch_assoc($beds)) {
                        $bed_list .= "<tr>";
                        $bed_list .= "<td  id='text-centre'>{$bed['bedNo']}</td>";

                            $bedNo = $bed['bedNo'];
                            if($bedNo){
                                $getPatientQuery = "SELECT first_name, last_name FROM `patients` WHERE  isDeleted = false AND bedID = {$bedNo} AND wardNo = {$wardNo} LIMIT 1 ";
                                $getPatient_result = mysqli_query($connection, $getPatientQuery);
                                $getPatient = mysqli_fetch_assoc($getPatient_result);

                                if($getPatient){
                                    $bed_list .= "<td>{$getPatient['first_name']} {$getPatient['last_name']}</td>";
                                    $filled_bed_count++;
                                }
                                else{
                                    $bed_list .= "<td>There is no patient</td>";
                                }

                            }

                            if($_SESSION['access'] == 'admin'){
                                $bed_list .= "<td>
                                                    <div class='action-container'>
                                                        <a class='delete-button' href=\"delete-bed.php?bed_id={$bed['id']}&ward_id={$wardNo}\">Delete &nbsp <i class='fa-solid fa-trash-can'></i></a>
                                                    </div>
                                                </td>";
                            }

                        $bed_list .= "</tr>";
                    }

                    // getting the head doctor name
                    $query = "SELECT headDocID FROM wards WHERE id = {$wardNo} LIMIT 1";
                    $wardDocResult = mysqli_query($connection, $query);
                    verify_query($wardDocResult);
                    $wardDoc = mysqli_fetch_assoc($wardDocResult);
                    $docID = $wardDoc['headDocID'];

                    $query = "SELECT * FROM doctors WHERE id = {$docID} LIMIT 1";
                    $DocResult = mysqli_query($connection, $query);
                    verify_query($DocResult);
                    $Doc = mysqli_fetch_assoc($DocResult);
                    $HeadDoc = $Doc['name'];
                
                    $haveResults = true;
                }
                else {
					// Ward Number invalid
					$errors[] = "You'r entered ward number is invalid";
				}
        }    
                

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
    <title>Manage beds - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Beds Management</h1>
            <hr/>

                <div class="bed-search">
                    <form action="ward-bed.php" method="post" >
                            <input type="number" name="ward_no" placeholder="Entre Ward Number" maxlength="5"   <?php echo 'value="' . $wardNo . '"'; ?> >
                            <button type="submit" name="submit">View Details</button>
                        <?php 
                            if (!empty($errors)) {
                                display_errors($errors);
                            }
                        ?>

                    </form>
                </div>

            <?php
                if ($haveResults) {
                    include './php/components/ward-bed-table.php';
                } 

                if($error){
                    echo  "<script type='text/javascript'>
                            alert('$error');
                            </script>";
    
                }
            ?>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>