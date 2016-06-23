<?php
require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php");  
    
if (!empty($_GET['getlist']))
    {
        $list = $_GET['getlist'];
        $SQL = '';
        switch ($list)
        {
            case 'users':
            {
                $users = new Users();
                $retvar = $users->getUserList();
                break;    
            } //end users
            case 'projects':
            {
                $projects = new Projects();
                $retvar = $projects->getProjectList();
                break;
            }
            case 'issues':
            {
                $issuetypes = new IssueTypes();
                $retvar = $issuetypes->getIssueTypeList();
                break;
            }
            case 'components':
            {
                $components = new Components();
                $retvar = $components->getComponentList();
                break;
            }
            case 'versions':
            {
                $versions = new Versions();
                $retvar = $versions->getVersionList();
                break;
            }
        }

   if (!empty($retvar)) {
        foreach ($retvar as $r){
            echo '<option>' . htmlspecialchars($r[0]) . '</option>';    
        }    
    }
}
?>