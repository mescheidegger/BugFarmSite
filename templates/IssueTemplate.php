<script id="issue-template" type="text/html">

    <?php
        include($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/AccountRegistration/GetSessionUser.php");
    ?>
    
<div id="issue-content" class= "issue-edit-form">
            <header id="issueheader" class="issue-header">
                <div class="issue-header-content">
                    <header class="imago-page-header">
                    <div class="imago-page-header-inner">
                        <div class="imago-page-header-main">
                        <ol class="imago-nav">
                        <li id="key-val">{{IKey}}</li>
                        </ol> 
                        <h1 id="summary-val">{{Summary}}</h1> 
                        </div>    
                        <div class="imago-page-header-actions">
                            
                        </div>
                    </div> <!-- end imagio page header inner -->
                    </header>
                    <div class="command-bar">
                        <div class="ops-cont">
                            <div class="command-bar-inner">
                                <div class="command-bar-toolbar">
                                    <div class="command-toolbar-left">
                                        <ul id="edit-issue" class="toolbar-group">
                                            <li class="toolbar-item">
                                                <a id="edit-issue-button" href="#" class="imago-button">
                                                    <span class="trigger-label">Edit</span>
                                                </a>    
                                            </li>
                                        </ul>
                                        
                                        <ul id="comment-issue" class="toolbar-group">
                                            <li class="toolbar-item">
                                                <a id="comment-issue-button" href="#" class="imago-button">
                                                    <span class="trigger-label">Comment</span>
                                                </a>    
                                            </li>
                                        </ul>
                                        
                                        <ul id="assign-issue" class="toolbar-group">
                                            <li class="toolbar-item">
                                                <a id="assign-issue-button" href="#" class="imago-button">
                                                    <span class="trigger-label">Assign</span>
                                                </a>    
                                            </li>
                                        </ul>
                                        
                                        <ul id="status-issue" class="toolbar-group">
                                            <li class="toolbar-item">
                                                <a id="resolve-issue-button" href="#" class="imago-button">
                                                    <span class="trigger-label">Resolve</span>
                                                </a>    
                                            </li>
                                            <li class="toolbar-item">
                                                <a id="close-issue-button" href="#" class="imago-button">
                                                    <span class="trigger-label">Close</span>
                                                </a>    
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <div class="command-toolbar-right">
                                    </div>        
                                </div>                           
                            </div>
                       </div>    
                    </div>    
                </div>    
            </header>
            <div class="issue-body-content">
                <div class="issue-body">
                    <div class="imago-item issue-main-column"> 
                        <div id="details-module" class="module toggle-wrap">
                            <div id="details-module-heading" class="mod-header">
                            <ul class="ops"></ul>
                            <h2 class="toggle-title"> Details </h2>
                            </div>
                            <div class="mod-content">
                             <!-- details about the issue built here -->
                                <ul id="issuedetails" class="property-list">
                                    <li class="item">
                                        <div class="wrap">
                                            <strong class="name">Type:</strong>
                                            <span id="type-val" class="value">{{IssueType}} </span>    
                                        <div>
                                    </li>
                                    <li class="item item-right">
                                        <div class="wrap">
                                            <strong class="name">Status:</strong>
                                            <span id="status-val" class="value">{{Status}} </span> 
                                        </div>
                                    <li>
                                    <li class="item">
                                        <div class="wrap">
                                            <strong class="name">Priority:</strong>
                                            <span id="priority-val" class="value">{{Priority}} </span>    
                                        <div>
                                    </li>
                                    <li class="item item-right">
                                        <div class="wrap">
                                            <strong class="name">Resolution:</strong>
                                            <span id="resolution-val" class="value"> {{Resolution}} </span> 
                                        </div>
                                    <li>
                                    <li class="item">
                                        <div class="wrap">
                                            <strong class="name">Affects Version/s:</strong>
                                            <span id="affect-val" class="value">{{AFFECTVERSIONS}} </span>    
                                        <div>
                                    </li>
                                    <li class="item item-right">
                                        <div class="wrap">
                                            <strong class="name">Fix Version/s:</strong>
                                            <span id="fix-val" class="value"> </span> 
                                        </div>
                                    <li>
                                </ul>   
                            </div>
                        </div>
                        <div id="description-module" class="module toggle-wrap">
                            <div id="description-module-heading" class="mod-header">
                            <ul class="ops"></ul>
                            <h2 class="toggle-title"> Description </h2>
                            </div>
                            <div class="mod-content">
                             <!-- description about the issue built here -->
                                <div id="description-val">
                                    <div class="user-content-block">
                                        {{Description}}
                                    </div>
                                    <!-- can add some invisible spacers here if needed -->    
                                </div>   
                            </div>
                        </div>
                        <div id="activity-module" class="module toggle-wrap">
                            <div id="activity-module-heading" class="mod-header">
                            <ul class="ops"></ul>
                            <h2 class="toggle-title"> Activity </h2>
                            </div>
                            <div class="mod-content">
                             <!-- activity about the issue built here --> 
                                <div class="tabwrap tabs2">
                                    <ul id="issue-tabs" class="tabs horizontal">
                                        <li class="active" id="comment-tabpanel">
                                            <strong tabindex="0">Comments </strong>
                                        </li>
                                    </ul>    
                                </div>
                                <div class="issuePanelWrapper">
                                    <div id="issue-activity-container" class="issuePanelContainer">
                                        <div class="message-container">
                                            There are no comments yet on this issue.
                                        </div>
                                        <!-- use .issue-data-block class for inserted comment divs -->
                                    </div>   
                                </div>      
                            </div>                        
                        </div>
                        <div id="addcomment" class="module">
                            <div id="addcomment" class="module">
                                <div class="mod-content">
                                <!-- form for add comments goes here -->
                                    <form id="addcommentform" style="display:none">
                                        <fieldset class="form-group">
                                            <label for="issue-comment-add">Comment</label>
                                            <textarea class="form-control" id="issue-comment-add" rows="5"></textarea>
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="mod-footer">
                                    <ul class="ops"> 
                                        <li> 
                                            <?php
                                            if ($user_name != ''){
                                                echo '<a href="#" id="footer-comment-button" data-fullname="' . $fname . ' ' . $lname . '" data-userid="' . $userid . '" data-token="' . $token . '" name="add-comment" class="imago-button">
                                                    <span id="commentlabel"> Comment </span>
                                                </a>';
                                            } else {
                                                echo '<div> Please log in to add comments</div>';
                                            }
                                            ?>
                                       </li>    
                                    </ul>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="viewissuesidebar" class="imago-item issue-side-column">
                        <div id="peoplemodule" class="module toggle-wrap">
                            <div id="peoplemodule_heading" class="mod-header">
                                <ul class="ops"></ul>
                                <h2 class="toggle-title">People</h2>
                            </div>
                            <div class="mod-content">
                                <ul class="item-details" id="peopledetails">
                                    <li class="people-details">
                                        <dl>
                                            <dt>Assignee:</dt>
                                            <dd><span id="assignee-val">{{ASSIGNEE}}</span></dd>   
                                        </dl>
                                        <dl>
                                            <dt>Reporter:</dt>
                                            <dd><span id="reporter-val">{{REPORTER}}</span></dd>   
                                        </dl>
                                    </li>    
                                </ul>  
                            </div>
                        </div>
                        <div id="datesmodule" class="module toggle-wrap">
                            <div id="datesmodule_heading" class="mod-header">
                                <ul class="ops"></ul>
                                <h2 class="toggle-title">Dates:</h2>
                            </div>
                            <div class="mod-content">
                                <ul class="item-details">
                                    <li>
                                        <dl class="dates">
                                            <dt>Created:</dt>
                                            <dd><span id="create-date">{{CREATED}}</span></dd>   
                                        </dl>
                                        <dl class="dates">
                                            <dt>Updated:</dt>
                                            <dd><span id="update-date">{{UPDATED}}</span></dd>   
                                        </dl>
                                    </li>    
                                </ul>  
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
        
        
</script>