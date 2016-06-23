var BUGFARMAPP = BUGFARMAPP || {
    
    serializeObject : function(a){
        var o = {};
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    },
    
    SEARCH : {
        
        INIT : function(){
            var BFSearch = BUGFARMAPP.SEARCH;
            $('#dashboard-content').on('click', '.pageresults', function(event){
                var pagenum = $(event.target).attr('pagenum');
                var searchterm = $(event.target).attr('title');
                BFSearch.SEARCHISSUES(searchterm, pagenum);
            });
        
            $('#searchresults').on('click', '.browsetoissue', function(event){
                var ikey = $(event.target).html();
                BFSearch.BROWSEISSUE(ikey);   
            });    
        },
        
        BROWSEISSUE : function(ikey){
            window.location.href = "/BugFarmSite/browse.php?issue=" + ikey;    
        },
        
        SEARCHISSUES : function(searchterm, pageval){
            $.ajax({
                type: "GET",
                url: "/BugFarmSite/PHP/search.php",
                dataType: "html",
                data: "searchterm=" + searchterm + "&page=" + pageval,
                success: function(response){
                        $("#searchresults").html(response);
                       // console.log(response);
                },
                error: function(response){
                    console.log(response);
                }
            });
     
        }    
    }, //end search
    
    ISSUES : {
        
        INIT : function() {
                var BFutil = BUGFARMAPP.UTIL;
                //LOAD DATA FROM SQL TO CREATE ISSUE SCREEN
                $('#createissuehref').click(function(){
                    var BFsql = BFutil.SQLTOOLS;
                    if (!$('#issuereporter').children("option").length) { //Populate this list on first load
                        BFsql.GetSQLData("/BugFarmSite/PHP/getlist.php?getlist=users", "#issuereporter");
                    }
                    
                    if (!$('#issueassignee').children("option").length) {
                        BFsql.GetSQLData("/BugFarmSite/PHP/getlist.php?getlist=users", "#issueassignee");
                    }
                    
                    if (!$('#projectdropdown').children("option").length) {
                        BFsql.GetSQLData("/BugFarmSite/PHP/getlist.php?getlist=projects", "#projectdropdown");
                    }
                    
                    if (!$('#issuetype').children("option").length) {
                        BFsql.GetSQLData("/BugFarmSite/PHP/getlist.php?getlist=issues", "#issuetype");
                    }
                    
                    /*
                        This needs to be updated to only show components for the selected Project
                        It also needs to be able to grab new data from SQL on change of the Project dropdown
                    */
                    if (!$('#projectcomponent').children("option").length) {
                        BFsql.GetSQLData("/BugFarmSite/PHP/getlist.php?getlist=components", "#projectcomponent");
                    } //By Default the TOP project is auto-filled. Using this to get component list on init
                    
                    /*
                        This needs to be updated to only show components for the selected Project
                        It also needs to be able to grab new data from SQL on change of the Project dropdown
                    */ 
                    var af = $('#affectversions');
                    af.multiselect({
                        includeSelectAllOption: true
                    });
                    
                    if (!af.children("option").length) {
                        BFsql.GetSQLDataMS("/BugFarmSite/PHP/getlist.php?getlist=versions", "#affectversions");
                    }; //By Default the TOP project is auto-filled. Using this to get component list on init
                    
                    
                    
                });
                
                var cf = $("#create-form");
                cf.submit(function(){
                    var newissue = new BUGFARMAPP.ISSUES.ISSUE(cf.serializeArray());
                    newissue.createIssue("/BugFarmSite/PHP/createissue.php");    
                });
                
                $('#search-form').submit(function(){
                    var searchterm = $('#searchterm').val();
                    if (searchterm != undefined && searchterm != null){
                        window.location.href = "/BugFarmSite/issues.php?searchterm=" + searchterm;
                        return false; //cancel default form submit
                    }    
                }); 
  
        }, //end init
        
        ISSUE : function(issuedata){
            var vars = {};
            $.extend(vars, issuedata);
            vars = BUGFARMAPP.serializeObject(vars); //truncate the JSON format from the serialized array
            
            
            var setIssue = function(issuedata){ //For browsing we will initalize a blank issue at first and set it later
                $.extend(vars, issuedata);
            };
            
            var loadIssue = function(){
                return $.get('/BugFarmSite/templates/IssueTemplate.php', function(templates) {
                    // Fetch the <script /> block from the loaded external
                    // template file which contains our greetings template.
                    var template = $(templates).filter('#issue-template').html();
                    $('#issue-container').append(Mustache.render(template, vars));
                    
                    $('#edit-issue-button').click(function(){
                        editIssue();    
                    }); 
                    
                    var fcb = $('#footer-comment-button');
                    fcb.click(function(){
                        var acf = $('#addcommentform');
                        if (acf.is(":hidden")) { //slide down the comment text area
                            acf.slideDown(250);
                            $('#commentlabel').text('Add');  
                        } else { //handle adding the comment to the issue
                            var tac = $('textarea#issue-comment-add');
                            var comment = tac.val(); //.replace(/\r?\n/g,'<br>'); //perserve newlines
                            var now = $.format.date(Date(),'dd/MM/yyyy HH:mma');
                            if (comment != ''){
                                var commentdata = {
                                    "comment":comment,
                                    "username": fcb.attr('data-fullname'),
                                    "userid":fcb.attr('data-userid'),
                                    "Ikey":$('#key-val').text(),
                                    "date":now,
                                    "token":fcb.attr('data-token')
                                };
                                var newcomment = new BUGFARMAPP.ISSUES.COMMENT(commentdata);
                                newcomment.addComment();
                                tac.val('');
                            }
                            acf.hide();    
                            $('#commentlabel').text('Comment'); 
                        }
                    });
                });
            };
        
        var getIssue = function(ikey){
            return $.ajax({
                type: "GET",
                url: "/BugFarmSite/PHP/getissue.php",
                dataType: "json",
                data: "ikey=" + ikey,
                success: function(response){
                    setIssue(response);
                    //console.log(response);   
                    //should load data to issue here
                },
                error: function(response){
                    console.log(response);
                    console.log('here?');
                    //404 not found redirect
                }    
            });
    
        }
            
        var editIssue = function(){
            console.log('here');    
        };
        
        var resolveIssue = function() {
            
        };
    
        var closeIssue = function() {
            
        };
        
        var createIssue = function(url){
            $.ajax({
                type: "POST",
                url: url,
                data: {action:JSON.stringify(vars)},
                success: function(result){
                    location.reload();
                    //console.log(result);     
                },
                error: function(result){
                    console.log(result);
                } 
            }); 
        };
        
        return {
          setIssue: setIssue,
          loadIssue: loadIssue,
          getIssue: getIssue,
          editIssue: editIssue,
          resolveIssue: resolveIssue,
          closeIssue: closeIssue,
          createIssue: createIssue  
        };
            
                
        }, //end issue
        
        COMMENT : function(commentdata){
            var vars = {}; //comment data in json format
            $.extend(vars, commentdata);
            
            var commenttemplate = '<div id="{{commentid}}" class="issue-data-block"><div class="action-container">' +
            '<div class="action-head">{{username}} added a comment - {{date}}</div>' +
            '<div class="action-body">{{comment}}</div>' +
            '</div></div>';
            
            
            var appendComment = function(comment){
                var output = Mustache.render(commenttemplate, comment);
                $('#issue-activity-container').append(output);
                $('.message-container').hide();
                var mc = $('.message-container');
                if (!mc.is(":hidden")) {
                    mc.hide();
                }   
            };
            
            var buildCommentList = function(ikey){
                $.ajax({
                    type: "GET",
                    url: "/BugFarmSite/PHP/getcommentlist.php",
                    dataType: "json",
                    data: "ikey=" + ikey,
                    success: function(response){
                        $.each(response, function(index, element){
                        appendComment(element); 
                        });

                    },
                    error: function(response){
                        console.log(response);
                        //404 not found redirect
                    }    
                });    
            };
                
            var addComment = function(){
                $.ajax({
                    type: "POST",
                    url: 'PHP/addcomment.php',
                    data: {action:JSON.stringify(vars)},
                    success: function(result){
                        var jsonComment = JSON.parse(result);
                        var addedComment = jsonComment[0];
                        $.extend(vars, addedComment);
                        appendComment(vars);    
                    },
                    error: function(result){
                        console.log(result);
                    } 
                });
            };
            
            return {
                buildCommentList : buildCommentList,
                addComment : addComment    
            };            
            
        } //end comment
        
    }, //end issues
    
    USERS : {
        
        sessionTimer: null,
        
        checkSession: function(){
                $.ajax({
                    type: "POST",
                    url: "/BugFarmSite/AccountRegistration/CheckSessionActive.php",
                    cache: false,
                    success: function(result){
                        if (result == '1'){
                            alert("Looks like you left your desk. I'm going to need you to log in again.");
                            window.clearInterval(BUGFARMAPP.USERS.sessionTimer);
                            window.location.href = "/BugFarmSite/Dashboard.php";
                        };
                    },
                    error: function(result){
                        console.log(result);
                    }
                });    
        },
        
        startSessionTimer: function() {
            $.ajax({
                    type: "POST",
                    url: "/BugFarmSite/AccountRegistration/CheckSessionActive.php",
                    cache: false,
                    success: function(result){
                        if (result == '0'){
                            console.log('here');
                            var BFUsers = BUGFARMAPP.USERS;
                            BFUsers.sessionTimer = setInterval(BFUsers.checkSession, 1000);
                        };
                    },
                    error: function(result){
                        console.log(result);
                    }
                });
            
              
        },
        
        USER: function(userdata){
            var vars = {};
            $.extend(vars, userdata);
            vars = BUGFARMAPP.serializeObject(vars);            
            
            var validateLogin = function(){
                $.ajax({
                    type: "POST",
                    url: "/BugFarmSite/AccountRegistration/Login.php",
                    data: {action:JSON.stringify(vars)},
                    cache: false,
                    success: function(result){
                        if (result == 'Email or password does not exist.'){
                            //need handling for bad email or pw
                            alert('Incorrect email or password.')
                        } else {
                           BUGFARMAPP.USERS.startSessionTimer();
                           location.reload();
                        }
                    },
                    error: function(result){
                        console.log(result);
                    }
                }); 
            };
            
            var registerAccount = function(){
                $.ajax({
                    type: "POST",
                    url: "/BugFarmSite/AccountRegistration/Register.php",
                    data: {action:JSON.stringify(vars)},
                    cache: false,
                    success: function(result){
                        alert("Account Registration Successful - Please log in");
                        location.reload();
                    },
                    error: function(result){
                        console.log(result);
                    }
                }); 
            };
            
            var lostPassword = function(){};
            
            var logout = function(){
                $.ajax({
                    type: "POST",
                    url: "/BugFarmSite/AccountRegistration/Logout.php",
                    data: 'confirmed=yes',
                    cache: false,
                    success: function(result){
                        //alert(result);
                        location.reload();
                    },
                    error: function(result){
                        console.log(result);
                    }
                });     
            };
            
            return {
                validateLogin: validateLogin,
                registerAccount: registerAccount,
                lostPassword: lostPassword,
                logout: logout, 
            };
                
        } //end USER
    },
    
    UTIL : {
        
        divForms: $('#div-forms'),
        
        modalAnimate: function (oldForm, newForm) {
            var oldH = oldForm.height();
            var newH = newForm.height();
            var modalAnimateTime = 300;
            BUGFARMAPP.UTIL.divForms.css("height",oldH);
            oldForm.fadeToggle(modalAnimateTime, function(){
                BUGFARMAPP.UTIL.divForms.animate({height: newH}, modalAnimateTime, function(){
                    newForm.fadeToggle(modalAnimateTime);
                });
            });
        },
        
            //grab items out of URLS being passed around
        getUrlParameter: function (sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        },
    
        SQLTOOLS: {
            GetSQLData: function (url, id){
                $.ajax({
                    type: "GET",
                    url: url,
                    dataType: "html",
                    success: function(response){
                        $(id).html(response);
                    },
                    error: function(response){
                        console.log(response);
                    }
                });  
            }, 
            
            GetSQLDataMS: function (url, id){
                    var buildList = $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "html",
                        success: function(response){
                            $(id).html(response);
                        },
                        error: function(response){
                            console.log(response);
                        }
                    });
                buildList.complete(function(){
                    if ($(id).children("option").length) {
                        $(id).multiselect('rebuild');        
                    }
                    });    
                }    
        },
        
        VALIDATE: {
        
            UserValidation: function(){
                var formLogin = $('#login-form');
                var formLost = $('#lost-form');
                var formRegister = $('#register-form');
                var BFutil = BUGFARMAPP.UTIL;
                var BFusers = BUGFARMAPP.USERS;
                
                $('#login_register_btn').click( function () { BFutil.modalAnimate(formLogin, formRegister); });
                $('#register_login_btn').click( function () { BFutil.modalAnimate(formRegister, formLogin); });
                $('#login_lost_btn').click( function () { BFutil.modalAnimate(formLogin, formLost); });
                $('#lost_login_btn').click( function () { BFutil.modalAnimate(formLost, formLogin); });
                $('#lost_register_btn').click( function () { BFutil.modalAnimate(formLost, formRegister); });
                $('#register_lost_btn').click( function () { BFutil.modalAnimate(formRegister, formLost); });
                
                $('#logout-form').submit(function(){
                    var userSession = new BFusers.USER('');
                    userSession.logout();    
                });
                
                formLost.validate({
                    invalidHandler: function(event, validator){
                        
                    },
                    showErrors: function(errorMap, errorList){
                        this.defaultShowErrors();
                        BFutil.divForms.css("height",formLost.height());   
                    },
                    submitHandler: function(form){

                    }    
                });
                
                formLogin.validate({
                    rules: {
                        uname: "required",
                        pwd: "required",
                    },
                    messages: {
                        uname: "Please enter your username",
                        pwd: "Please provide a password"        
                    },
                    invalidHandler: function(event, validator){
                        
                    },
                    showErrors: function(errorMap, errorList){
                        this.defaultShowErrors();
                        BFutil.divForms.css("height",formLogin.height());   
                    },
                    submitHandler: function(form){
                        var userSession = new BFusers.USER(formLogin.serializeArray());
                        userSession.validateLogin();     
                    }
                });
                
                formRegister.validate({
                    rules: {
                        fname: "required",
                        lname: "required",
                        uname: "required",
                        email: {
                            required: true,
                            email: true
                        },
                        pwd: {
                            required: true,
                            minlength: 6
                        }
                    }, //end rules
                    messages: {
                        fname: "Please enter your first name",
                        lname: "Please enter your first name",
                        uname: "Please enter your username",
                        email: {
                            required: "Please provide an e-mail address",
                            email: "Please provide a valid e-mail address"
                        },
                        pwd: {
                            required: "Please provide a password",
                            minlength: "Please provide a password with at least 6 characters"
                        }    
                        
                        },
                        invalidHandler: function(event, validator){
                            
                        },
                        showErrors: function(errorMap, errorList){
                            this.defaultShowErrors();
                            BFutil.divForms.css("height",formRegister.height());   
                        },
                        submitHandler: function(form){
                        var userSession = new BFusers.USER($("#register-form").serializeArray());
                        userSession.registerAccount();     
                        }
                        
                    });
                    
                } 
            } //end validate    
    } //end util
    
};

$(function() {
    //client side clickjacking protection
    if (self == top) {
        document.documentElement.style.display = 'block';
    } else {
        top.location = self.location;
    }
    
    var body = $('body'); 
    
    if (body.is('.dashboard-body')){ 
        //initdashboard    
    } else if (body.is('.browse-body')) {
        var issue = BUGFARMAPP.UTIL.getUrlParameter('issue');
        if (issue === undefined){
            //404 should not happen
        } else {
            var BFIssues = BUGFARMAPP.ISSUES;
            var existingIssue = new BFIssues.ISSUE('');
            var issueInitalized = existingIssue.getIssue(issue);
            issueInitalized.done(function(result){
                var issueLoaded = existingIssue.loadIssue();
                issueLoaded.done(function(result){
                    var commentlist = new BFIssues.COMMENT('');
                    commentlist.buildCommentList(issue);                    
                });    
            });        
        }
            
    } else if (body.is('.issues-body')) {
        var searchval = BUGFARMAPP.UTIL.getUrlParameter('searchterm');
        var pageval = BUGFARMAPP.UTIL.getUrlParameter('page');
        
        if (searchval === undefined){
            searchval = '';
        }
        if (pageval === undefined ){
            pageval = '0';
        }
        var BFSearch = BUGFARMAPP.SEARCH;
        BFSearch.SEARCHISSUES(searchval, pageval);
        BFSearch.INIT();
              
    }
    var BFutil = BUGFARMAPP.UTIL;
    var BFIssues = BUGFARMAPP.ISSUES;
    var BFUsers = BUGFARMAPP.USERS;
    BFutil.VALIDATE.UserValidation();
    BFIssues.INIT();
    
    function logger(){
      var d = new Date();
      var t = d.toLocaleTimeString();
      
      console.log(t);  
    };
    

   BFUsers.startSessionTimer();
    
    
    //var test = setInterval(logger, 1000);
                 
});
