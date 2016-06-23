    <?php
        //cookie works on all pages and expires every 30 minutes
        session_set_cookie_params(30 * 60, '/');
        session_start();
        session_regenerate_id(true);
        require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php"); 
        if(!isset($_SESSION['user_session'])) //if the user is not logged in set the following
        {
            $userid = '';
            $user_name = '';
            $fname = '';
            $lname = '';    
        }
        else { //If the user is logged in set the following
                $user = new Users();
                $retvar = $user->getUserSession($_SESSION['user_session']);
                $userid = $retvar[0]['ID'];
                $user_name = $retvar[0]['UNAME'];
                $fname = $retvar[0]['FIRST_NAME'];
                $lname = $retvar[0]['LAST_NAME'];
                $token = hash("sha256", mt_rand(), false);
                $_SESSION['submittoken'] = $token;
       }
    ?>
    