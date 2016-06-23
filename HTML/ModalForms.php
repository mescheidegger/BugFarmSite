    <!-- BEGIN # MODAL LOGIN -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" align="center">
                    <img class="img-circle" id="img_logo" src="http://bootsnipp.com/img/logo.jpg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                </div>
                
                <!-- Begin # DIV Form -->
                <div id="div-forms">
                
                    <!-- Begin # Login Form -->
                    <form id="login-form">
                        <div class="modal-body">
                            <div id="div-login-msg">
                                <div id="icon-login-msg" class="glyphicon glyphicon-chevron-right"></div>
                                <span id="text-login-msg">Type your username and password.</span>
                            </div>
                            <input id="login_username" name="uname" class="form-control" type="text" placeholder="Username" required>
                            <input id="login_password" name="pwd" class="form-control" type="password" placeholder="Password" required>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"> Remember me
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                            </div>
                            <div>
                                <button id="login_lost_btn" type="button" class="btn btn-link">Lost Password?</button>
                                <button id="login_register_btn" type="button" class="btn btn-link">Register</button>
                            </div>
                        </div>
                    </form>
                    <!-- End # Login Form -->
                    
                    <!-- Begin | Lost Password Form -->
                    <form id="lost-form" style="display:none;">
                        <div class="modal-body">
                            <div id="div-lost-msg">
                                <div id="icon-lost-msg" class="glyphicon glyphicon-chevron-right"></div>
                                <span id="text-lost-msg">Type your e-mail.</span>
                            </div>
                            <input id="lost_email" class="form-control" type="text" placeholder="E-Mail (type ERROR for error effect)" required>
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                            </div>
                            <div>
                                <button id="lost_login_btn" type="button" class="btn btn-link">Log In</button>
                                <button id="lost_register_btn" type="button" class="btn btn-link">Register</button>
                            </div>
                        </div>
                    </form>
                    <!-- End | Lost Password Form -->
                    
                    <!-- Begin | Register Form -->
                    <form id="register-form" style="display:none;">
                        <div class="modal-body">
                            <div id="div-register-msg">
                                <div id="icon-register-msg" class="glyphicon glyphicon-chevron-right"></div>
                                <span id="text-register-msg">Register an account.</span>
                            </div>
                            <input id="register_fname" name="fname" class="form-control" type="text" placeholder="First Name" required>
                            <input id="register_lname" name="lname" class="form-control" type="text" placeholder="Last Name" required>
                            <input id="register_username" name="uname" class="form-control" type="text" placeholder="Username" required>
                            <input id="register_email" name="email" class="form-control" type="text" placeholder="E-Mail" required>
                            <input id="register_password" name="pwd" class="form-control" type="password" placeholder="Password" required>
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button>
                            </div>
                            <div>
                                <button id="register_login_btn" type="button" class="btn btn-link">Log In</button>
                                <button id="register_lost_btn" type="button" class="btn btn-link">Lost Password?</button>
                            </div>
                        </div>
                    </form>
                    <!-- End | Register Form -->
                    
                </div>
                <!-- End # DIV Form -->
                
            </div>
        </div>
    </div>
    <!-- END # MODAL LOGIN -->
    
    
    <!-- BEGIN MODAL LOGOUT -->
    <div class="modal fade" id="logout-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" align="center">
                    <img class="img-circle" id="img_logo" src="http://bootsnipp.com/img/logo.jpg">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                </div>
    
        <!-- Begin # Logout Form -->
        <form id="logout-form">
            <div class="modal-body">
                <div id="div-logout-msg">
                    <div id="icon-login-msg" class="glyphicon glyphicon-chevron-right"></div>
                    <span id="text-login-msg">Are you sure you want to logout of BugFarm?</span>
                </div>
            </div>
            <div class="modal-footer">
                <div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Logout</button>
                </div>
            </div>
        </form>
        <!-- End # Logout Form -->
                
                
           </div>
        </div>
    </div>
    <!-- END # MODAL LOGOUT --> 
    <!-- BEGIN MODAL CREATE -->
    <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" align="center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    </button>
                    <p align="left"><strong><font size="6"> Create Issue </font> </strong></p>
                </div>
                <!-- Begin # create issue form -->
                <form name= "createissue" id="create-form">
                    <div class="modal-body">                                                        
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="projectdropdown"> Project </label>
                                </div>
                                <div class="col-md-4">
                                <select class="form-control" name="project" id="projectdropdown">
                                
                                </select>
                                </div>
                            </div>
                            </div>
                            
                            
                            
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="issuetype"> Issue Type </label>
                                </div>
                                <div class="col-md-4">
                                <select class="form-control" name="type" id="issuetype">
                                </select>
                                </div>
                            </div>
                            </div>
                            
                            <hr style="width: 100%; color: black; height: 1px; background-color:black;" />
                            
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="issuesummary"> Summary </label>
                                </div>
                                <div class="col-md-8">
                                <input type="text" class="form-control" name="summary" id="issuesummary">
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="issuepriority"> Priority </label>
                                </div>
                                <div class="col-md-4">
                                <select class="form-control" name="priority" id="issuepriority">
                                <option> Minor </option>
                                <option> Major </option>
                                <option> Critical </option>
                                <option> Blocker </option>
                                </select>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="projectcomponent"> Component </label>
                                </div>
                                <div class="col-md-4">
                                <select class="form-control" name="component" id="projectcomponent">
                                </select>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="affectversions"> Affected Product Version </label>
                                </div>
                                    <div class="col-md-4" id='avcol'>
                                        <select id="affectversions" name="versions" multiple="multiple"> 

                                        </select>
                                    </div> 
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="issueassignee"> Assignee </label>
                                </div>
                                <div class="col-md-4">
                                <select class="form-control" name="assignee" id="issueassignee">
                                </select>
                                </div>
                            </div>
                            </div>
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="issuereporter"> Reporter </label>
                                </div>
                                <div class="col-md-4">
                                <select name="reporter" class="form-control" id="issuereporter">

                                </select>
                                </div>
                            </div>
                            </div>
                            
                            <div class="form-group">
                            <div class="row" id="issuerow">
                                <div class="col-md-1"></div>
                                
                                <div class="col-md-3" id="issuelabelcolumn">
                                <label class='issuedropdownlabel' for="issued"> Description </label>
                                </div>
                                
                                <div class="col-md-8">
                                <textarea name="description" id="issued" rows="8"></textarea>
                                </div>
                            
                            </div>
                            </div>
                            <div class="form-group">
                            <?php 
                                echo '<input type="hidden" name="token" value="' . $token . '"/>'; 
                            ?>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Create Issue</button>
                        </div>
                    </div>
                </form>
                <!-- End # Create Issue Form -->   
              </div>
        </div>
    </div>
    <!--END MODAL CREATE -->