<?php require_once('./php/connection.php'); ?>

<html lang="en">
<head>
    <link rel="stylesheet" href="./CSS/login.css">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log In - HMS</title>
</head>
<body class="login-body">
            <div class="login">

                    <form action="index.php" method="post">
                        <fieldset class="l">
                            <legend><h1>Admin Log In</h1></legend>

                            <!-- <p class="error">Invalid Username / Password</p> -->

                                <label for="">Username:</label>
                                <input class="login-input" type="text" name="email" id="" placeholder="Email Address">
                                <br/>

                                <label for="">Password:</label>
                                <input class="login-input" type="password" name="password" id="" placeholder="Password">
                                <br/><br/>

                                <button class="login-button" type="submit" name="submit">Log In</button>
                        </fieldset>
                    </form>		

                </div> <!-- .login -->    
</body>
</html>

<?php mysqli_close($conn); ?>