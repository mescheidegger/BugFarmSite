<?php 
   require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php");
   if (isset($_GET['ikey'])) {
      $ikey = $_GET['ikey'];
      $issue = new Issues();
      $retvar = $issue->getIssue($ikey);

      $retvar[0]['CREATED'] = date('Y-m-d H:i:s', $retvar[0]['CREATED']->getTimeStamp());
      $retvar[0]['UPDATED'] = date('Y-m-d H:i:s', $retvar[0]['UPDATED']->getTimeStamp());
      
      $user = new Users();
      $retvar[0]['ASSIGNEE'] = $user->getUserFNLN($retvar[0]['ASSIGNEE']);
      $retvar[0]['REPORTER'] = $user->getUserFNLN($retvar[0]['REPORTER']);
      
      $versions = new Versions();
      $retvar[0]['AFFECTVERSIONS'] = $versions->getVersions($retvar[0]['ID'], 'AffectVersion');
      echo json_encode($retvar[0]);
   }
?>