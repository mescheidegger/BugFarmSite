<?php   
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php");
    if (isset($_POST['action'])){
        $issuedata = json_decode($_POST['action'], true);
        
        $token = $issuedata['token'];
        if ($token === $_SESSION['submittoken']) {
            $issueproject = $issuedata['project'];
            $issuetype = $issuedata['type'];
            $issuesummary = $issuedata['summary'];
            $issuepriority = $issuedata['priority'];
            $projectcomponent = $issuedata['component'];
            $av = $issuedata['versions'];
            $issueassignee = $issuedata['assignee'];
            $issuereporter = $issuedata['reporter'];
            $issued = $issuedata['description'];
            $affectversions = '';
            if (is_array($av)) {
                $j = 0;
                foreach ($av as $v){
                    $affectversions = $j > 0 ? $affectversions = $affectversions . ', ' . $v : $v;        
                    $j++;
                }
            } else {
                $affectversions = $av;
            }
            $params = array($issueproject, $issuereporter, $issueassignee, $issuetype, $issuesummary, $issued, $issuepriority);
            $issue = new Issues();
            $issue->createIssue($params, $affectversions);
        }
        else {
            echo 'CSRF Attack Occured - Logging Needed';
        } 
    }
    
?>