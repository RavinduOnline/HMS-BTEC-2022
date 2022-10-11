<?php session_start(); ?>
<?php require_once('./php/connection.php'); ?>
<?php require_once('./php/functions.php'); ?>

<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
	}

    $errors = array();

    $nurse_list = '';
    $wardNo = '';
    $nurse_result = '';
    $nurse_count = '';
    $filled_nurse_count = 0;
    $HeadDoc = '';
    $docID = '';
    $haveResults= false;

    if (isset($_POST['submit'])) {

        $wardNo = $_POST['ward_no'];

        // checking required fields
		$req_fields = array('ward_no');
		$errors = array_merge($errors, check_req_fields($req_fields));

        // checking max length
		$max_len_fields = array('ward_no' => 2);
        $errors = array_merge($errors, check_max_len($max_len_fields));

        if (empty($errors)) {
                $wardNo = mysqli_real_escape_string($connection, $_POST['ward_no']);
                
                $query = "SELECT * FROM wards WHERE id = {$wardNo}";
                $ward = mysqli_query($connection, $query);

                // getting the list of nurse
                $query = "SELECT * FROM nurses WHERE wardNo = {$wardNo}";
                $nurses = mysqli_query($connection, $query);
               


                if(mysqli_num_rows($ward) >= 1){
                    $nurses_count = mysqli_num_rows( $nurses );

                    while ($nurse = mysqli_fetch_assoc($nurses)) {
                        $nurse_list .= "<tr>";
                        $nurse_list .= "<td  id='text-centre'>{$nurse['id']}</td>";
                        $nurse_list .= "<td  id='text-centre'>{$nurse['name']}</td>";
                        $nurse_list .= "<td  id='text-centre'>{$nurse['type']}</td>";
                        $nurse_list .= "</tr>";
                    }

                    // getting the head doctor name
                    $query = "SELECT headDocID FROM wards WHERE id = {$wardNo} AND  isDeleted = false LIMIT 1";
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
    <title>Manage Ward Staff - HMS</title>
</head>
<body>
   <!--Header call-->
   <?php include './php/components/sidebar.php' ?>

    <div class="body-text">
            <h1 class="page-main-title">Ward Staff Management</h1>
            <hr/>

                <div class="bed-search">
                    <form action="ward-staff.php" method="post" >
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
                    include './php/components/ward-staff-table.php';
                } 
            ?>

    </div>

    <div>
        <!--Footer call-->
        <?php include './php/components/footer.php' ?>
    </div>
</body>
</html>