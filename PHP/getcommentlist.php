<?php 
   require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php");
   if (isset($_GET['ikey'])) {
      $ikey = $_GET['ikey'];
      $issue = new Issues();
      $retvar = $issue->getCommentList($ikey);
      $j = 0;
      foreach ($retvar as $v){
          $retvar[$j]['date'] = date('d-m-Y H:iA', $v['date']->getTimeStamp());
          $j++;
      }
      echo json_encode($retvar);
   }
?>