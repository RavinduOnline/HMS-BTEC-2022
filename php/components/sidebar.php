
<div class="sidebar">
        <div class="logo-container">
            <img class="header-logo" src="./Images//logo/hospital-management-system-logo-dark.png" alt="Logo"/>
        </div>
        <div>
            <div class="user-box">
                <div class="user-box-container1">
                    <img class="useer-avatr-image" src="https://as2.ftcdn.net/v2/jpg/02/90/56/39/1000_F_290563992_aHXBzKMHLQnwtpbACWlhgrWnZtsvPFnp.jpg" alt="User Avatar"/>
                    <div class="user-box-name-container">
                        <div class="user-name"><?php echo $_SESSION['name']; ?></div>
                        <div class="user-position"><?php echo $_SESSION['role']; ?></div>
                    </div>
                </div>
                <div class="logout-box">
                    <a href="logout.php">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
           
        </div>
        
        <div class="main-menu">
        <?php

                    echo ' <a href="index.php" class="menu-item-link"> <i class="fa-solid fa-house-medical"></i> &nbsp Dashboard</a>';
                if($_SESSION['access'] == "admin"  || $_SESSION['access'] =="nurse"){
                    echo '<a href="patient.php" class="menu-item-link"><i class="fa-solid fa-hospital-user"></i> &nbsp Manage Patients</a>';
                    echo '<a href="ward.php" class="menu-item-link"><i class="fa-solid fa-bed-pulse"></i> &nbsp Manage Ward</a>';
                }
                if($_SESSION['access'] =="doctor" ){
                    echo '<a href="doc-opd-patient.php" class="menu-item-link"><i class="fa-solid fa-hospital-user"></i> &nbsp Manage OPD Patients</a>'; 
                    echo '<a href="doc-ward-patient.php" class="menu-item-link"><i class="fa-solid fa-person-shelter"></i> &nbsp Manage Ward Patients</a>';
                    echo '<a href="ward.php" class="menu-item-link"><i class="fa-solid fa-bed-pulse"></i> &nbsp Manage Ward</a>';
                }
                if($_SESSION['access'] == "admin"){
                    echo '<a href="doctor.php" class="menu-item-link"><i class="fa-solid fa-stethoscope"></i> &nbsp Manage Doctors </a>';
                };
                if($_SESSION['access'] == "admin" || $_SESSION['access'] =="doctor"){
                    echo ' <a href="nurse.php" class="menu-item-link"><i class="fa-sharp fa-solid fa-user-nurse"></i> &nbsp Manage Nurses</a>';
                }
                
                if($_SESSION['access'] == "admin"){
                    echo '<a href="staff.php" class="menu-item-link"><i class="fa-solid fa-user-tie"></i> &nbsp Manage Staffs</a>';
                    echo '<a href="admin.php" class="menu-item-link"><i class="fa-solid fa-toolbox"></i> &nbsp Manage Admins</a>';
                }

              
            ?>

        </div>
        
</div>