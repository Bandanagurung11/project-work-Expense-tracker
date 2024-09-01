<?php
    session_start();
    session_destroy();
    echo '<script>';
    echo 'window.location.href = "http://localhost/cashtrack/index.html";';
    echo '</script>';
?>