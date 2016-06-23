<?php
    session_start();
    $logout = $_POST['confirmed'];
    
    If ($logout == 'yes'){
        unset($_SESSION['user_session']);
    }

?>