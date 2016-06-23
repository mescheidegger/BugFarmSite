<?php 
require_once($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/includes/DatabaseAccess.php");

$rowlimit = 50;
//Get what was searched, doesn't matter if its blank or not
$searchterm = htmlspecialchars($_GET['searchterm']);
$search = new Search();
$versions = new Versions();
$users = new Users();
//Get the row count and make inital calculations for paging data if needed
$rowcount = $search->countrows($searchterm);

if(isset($_GET['page'])) {
    $page = $_GET['page'];
    $offset = $rowlimit * $page;
}else {
    $page = 0;
    $offset = 0;
}

$leftrow = $rowcount - ($page * $rowlimit);
//We are done figuring out paging numbers

//Search the database
$retvar = $search->searchdb($searchterm, $offset, $rowlimit);
//Build the table header
echo '<table class="table">
        <thread>
          <th> Type </th>
          <th> Key </th>
          <th> Summary </th>
          <th> Affect Versions </th>
          <th> Assignee </th>
          <th> Reporter </th>
          <th> Priority </th>
          <th> Status </th>
          <th> Resolution </th>
          <th> Created </th>
          <th> Updated </th>
       </thread><tbody>';

//Build the table of results
foreach ($retvar as $row){
       
       //Build string for the affect versions
       $affectversions = $versions->getVersions($row[0], 'AffectVersion');
       
       //Get Assignee and Report first/last names
       $assignee = $users->getUserFNLN($row[4]);
       $reporter = $users->getUserFNLN($row[5]);;
       
       //end building string for affect versions
       //echo table results
       echo '<tr><td>' . htmlspecialchars($row[1]) . '</td><td><div class="browsetoissue">' . htmlspecialchars($row[2]) . '</div></td><td>' . htmlspecialchars($row[3]) . '</td><td>' . htmlspecialchars($affectversions) . '</td><td>' . htmlspecialchars($assignee) . '</td>' .
       '<td>' . htmlspecialchars($reporter) . '</td><td>' . htmlspecialchars($row[6]) . '</td><td>' . htmlspecialchars($row[7]) . '</td><td>' . htmlspecialchars($row[8]) . '</td>' . 
       '<td>' . date('Y-m-d H:i:s', $row[9]->getTimeStamp()) . '</td><td>' . date('Y-m-d H:i:s', $row[10]->getTimeStamp()) . '</td></tr>';
   }

echo '</tbody></table>';

//Add div tag for paging if needed
echo '<div id="pageit" align="center">';
if ($page == 0 && $rowcount > $rowlimit){ //If we are on first page and the row count is more than our limit
    $page++;
    echo '<ul><li class="pageresults" title="' . $searchterm .'" pagenum="' . $page . '"> Next 50 Records </li></ul>';
} else if ($leftrow <= $rowlimit && $page != 0) { //if we have more rows left than our limit and are not on the first page
    $page = $page - 1;
    echo '<ul><li class="pageresults"title="' . $searchterm .'" pagenum="' . $page . '"> Last 50 Records </li></ul>';
} else if ($leftrow > $rowlimit && $page > 0) { //if we have more than 10 rows left and not on first page
    $last = $page - 1;
    $page++;
    echo '<ul><li class="pageresults" title="' . $searchterm .'" pagenum="' . $last . '"> Last 50 Records</li>';
    echo '<li>|</li>';
    echo '<li class="pageresults" title="' . $searchterm .'" pagenum="' . $page . '"> Next 50 Records </li></ul>';
}
echo '</div>';

?>
