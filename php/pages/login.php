<!DOCTYPE html>
<html lang="en">
<head>
    <!--Meta Tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Favicons-->
    <link rel="apple-touch-icon" sizes="57x57" href="../../assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="../../assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="../../assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="../../assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="../../assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="../../assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="../../assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="../../assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="../../assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!--Other-->
    <title>Sign in into CashTrack!</title>
    <!--CSS-->
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/fontawesome.all.min.css">
</head>
<body>

    <main>
        <div class="center-page container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h1 class="mb-3 mt-3">Sign in</h1>
                    <form action="login.php" method="POST" onsubmit="return validateLoginForm()">
                        <div class="form-group">
                          <label for="username">Username</label>
                          <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Enter Password" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary btn-lg">Submit</button>
                    </form>
                    <p class="pt-3">
                        Don't have an account? Create it
                        <a href="./signup.php">here.</a>
                    </p>
                </div>
                <div class="col-sm-12 col-md-6">
                    <img src="../../assets/illustrations/asset-2.png" class="image mb-3 mt-3" alt="banner">
                </div>
            </div>
        </div>
    </main>

    <!--JS-->
    <script src="../../js/form_validations.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  
</body>
</html>
<?php

// Connect to db.
include('../db/db_config.php');
include('../db/sql_queries.php');

session_start();  

if(isset($_POST['submit'])) {

    $username = $_POST['username'];
    $pass = $_POST['password'];

    // get user with username as given
    $query = get_user($conn,$username);
    $result = mysqli_query($conn,$query);

    if(!$result){
        //DB error
        navigate_to_login_page(mysqli_errno($conn));
    } else if(mysqli_num_rows($result) <= 0) {
        // no account with same username
        navigate_to_login_page("Username doesn't exist");
    } else {
        // Get first row of result (only one row should exist)
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  

        // strip and escape pass to prevent sql injection
        $striped_pass = stripcslashes($pass);
        $mysql_pass = mysqli_real_escape_string($conn,$striped_pass);

        //verify pass after hashing with hashed pass from db
        if(!password_verify($mysql_pass,$row['hash_pwd'])){
            // password mismatch
            navigate_to_login_page("Invalid password");
        } else {
            // login successful, set username in session
            $_SESSION['username'] = $username;
            navigate_to_dashboard();
        }
    }
} 
//navigate_to_login_page("Cannot get username and password");

function navigate_to_login_page($error) {
    echo '<script>';
    echo 'alert("Error : '.$error.'");';
    echo 'window.location.href = "http://localhost/cashtrack/php/pages/login.php";';
    echo '</script>';
}

function navigate_to_dashboard() {
    echo '<script>';
    echo 'window.location.href = "http://localhost/cashtrack/dashboard";';
    echo '</script>';
}

mysqli_close($conn);

?>