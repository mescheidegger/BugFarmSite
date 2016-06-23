<?php   
    require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php"); 
    if (isset($_POST['action'])){
        $userdata = json_decode($_POST['action'], true);

        $rg_fname = $userdata['fname'];
        $rg_lname = $userdata['lname'];
        $rg_username = $userdata['uname'];
        $rg_password = $userdata['pwd'];
        $rg_email = $userdata['email'];
        $params = array($rg_username, $rg_fname, $rg_lname, $rg_password, $rg_email);
        
        $users = new Users();
        $users->addUser($params);
    }
?>
