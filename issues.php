<?php
    include($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/HTML/Header.php");
?>

<body id="imagio" class="issues-body">
<?php
    include($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/HTML/ModalForms.php");
    include($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/HTML/NavBar.html");
?> 
  
  
  <section id="content" role="main">
    <div id="dashboard">
        <ul class="vertical tabs"> <!-- sidebar -->
            <li class="active first">
                <strong> <span title="Default Dashboard"> Default Dashboard </span> </strong>
            </li>   
        </ul> <!-- end sidebar -->
        <div id="dashboard-content"> <!-- body of page -->
            <div class="imago-page-header">
                <div class="imago-page-header-inner">
                    <div class="imago-page-header-main"><h1> Search </h1> 
                    </div>    
                    <div class="imago-page-header-actions">
                        
                    </div>
                </div> <!-- end imagio page header inner -->
            </div> <!-- end imagio page header -->
            <div class="imago-layout" id="searchresults">                        
            </div>  
        </div> <!-- end body of page -->
    
    </div>
</section>
  

  

<?php
    include($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/HTML/Footer.html");
?> 