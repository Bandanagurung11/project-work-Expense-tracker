<?php
    // Include db and queries
    include('../db/db_config.php');
    include('../db/sql_queries.php');

    session_start();

    if(!isset($_SESSION['username'])) {
        navigate_to_login_page("Error sending data!");
    } else {
        $username = $_SESSION['username'];

        mysqli_autocommit($conn,FALSE);
        $query_1 = delete_data($conn,$username);
        $result_1 = mysqli_query($conn,$query_1);
        $query_2 = reset_balances($conn,$username);
        $result_2 = mysqli_query($conn,$query_2);
        $commit = mysqli_commit($conn);
        mysqli_autocommit($conn,TRUE);

        if (!$commit) {
            navigate_to_settings_page("Database error");
        } else {
            navigate_to_dashboard_with_alert();
        }
    }
          
    function navigate_to_settings_page($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/cashtrack/dashboard/settings/index.php";';
        echo '</script>';
    }

    function navigate_to_login_page($error) {
        echo '<script>';
        echo 'alert("Error : '.$error.'");';
        echo 'window.location.href = "http://localhost/cashtrack/php/pages/login.php";';
        echo '</script>';
    }

    function navigate_to_dashboard_with_alert() {
        echo '<script>';
        echo 'alert("Reset data!");';
        echo 'window.location.href = "http://localhost/cashtrack/dashboard";';
        echo '</script>';
    }

    mysqli_close($conn);
?>