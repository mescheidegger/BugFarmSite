<?php   
   session_start();
   require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php");
   if (isset($_POST['action'])){
        $commentdata = json_decode($_POST['action'], true);
        $token = $commentdata['token'];
        if ($token === $_SESSION['submittoken']) {
            $comment = $commentdata['comment'];
            $user = $commentdata['userid'];
            $ikey = $commentdata['Ikey'];
            $issue = new Issues();
            $issue->addComment($ikey, $comment, $user);
            $retvar = $issue->getComment($ikey, $comment, $user);
            $j = 0;
            foreach ($retvar as $v){
            $retvar[$j]['date'] = date('d-m-Y H:iA', $v['date']->getTimeStamp());
            $j++;
            }
            echo json_encode($retvar);
            //tried to do this in one statement wasn't returning anything
            //definitly not ideal
        } else {
            echo 'CSRF Attack Occured - Logging Needed';
        }
   }
?>