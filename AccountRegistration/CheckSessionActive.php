<?php 
    session_start();
    $retvar = (!isset($_SESSION['user_session'])) ? '1' : '0'; 
    echo $retvar;
?>