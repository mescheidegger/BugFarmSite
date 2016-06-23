<?php   
    //cookie works on all pages and expires every 30 minutes
    session_set_cookie_params(30 * 60, '/');
    session_start();
    session_regenerate_id(true);
    require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php"); 
    if (isset($_POST['action'])){
        $userdata = json_decode($_POST['action'], true);
    
        $user_username = $userdata['uname'];
        $user_password = $userdata['pwd'];
        $users = new Users();
        
        $userid = $users->validateUser($user_username, $user_password);
        if ($userid === -1){
            echo "Email or password does not exist.";    
        } else {
            $_SESSION['user_session'] = $userid;    
        }
    }
    
?>
