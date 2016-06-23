<?php
    abstract class Db {
        
        protected static $conn;
        
        protected function connect() {
            if (!isset(self::$conn)) {
                $config = parse_ini_file($_SERVER['DOCUMENT_ROOT']."/BugFarmSite/config/dbconfig.ini");
                $connectionInfo = array('Database'=>$config['dbname'], 'UID'=>$config['username'], 'PWD'=>$config['password']);
                self::$conn = sqlsrv_connect($config['servername'], $connectionInfo);
            }
            
            if (self::$conn === false) {
                return false; //need error handling on client side DB connection could not be made
            } 
            
            return self::$conn; 
        }
        //select
        protected function query($SQL, $params, $fetch){ 
            $conn = $this->connect();
            if(empty($SQL)){ 
                echo "invalid params! "; exit; 
            } 
            
            $stmt = sqlsrv_query($conn, $SQL, $params);

            if( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true));               
            }
            
            $retvar = array();
            $j = 0;
            while ($row = sqlsrv_fetch_array($stmt, $fetch)) {
                $retvar[$j] = $row;
                $j++;
            }           
            return $retvar;            
        }
        
        //insert, update, or delete
        protected function commit($SQL, $params){
            $conn = $this->connect();
            if(empty($SQL)){ 
                echo "invalid params! "; exit; 
            } 
            
            $stmt = sqlsrv_query($conn, $SQL, $params);

            if( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true));               
            }
        }
  
        protected function __destruct(){
            sqlsrv_close($conn);
        }
        
        abstract protected function find($SQL, $params, $fetch);
        abstract protected function save($SQL, $params);

  }
    
    class Components extends Db{
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function getComponentList(){
            $SQL = "
                    BEGIN TRY
                        BEGIN TRANSACTION
                        
                        SELECT ComponentName
                        FROM BugFarmComponents
                        WHERE (PROJECT = (SELECT TOP 1 ID FROM BugFarmProjects))
                        
                        COMMIT TRANSACTION
                    END TRY
                    BEGIN CATCH
                        ROLLBACK TRANSACTION;
                        
                        PRINT ERROR_MESSAGE();
                            
                    END CATCH;
                ";
            return $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);         
        }    
    }
    
    class IssueTypes extends Db{
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function getIssueTypeList(){
            $SQL = "
                    BEGIN TRY
                        BEGIN TRANSACTION
                        
                        SELECT IType
                        FROM BugFarmIssueTypes
                        
                        COMMIT TRANSACTION
                    END TRY
                    BEGIN CATCH
                        ROLLBACK TRANSACTION;
                        
                        PRINT ERROR_MESSAGE();
                            
                    END CATCH;
                ";
            return $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);         
        }    
    }
    
    class Projects extends Db{
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function getProjectList(){
            $SQL = "
                    BEGIN TRY
                        BEGIN TRANSACTION
                        
                        SELECT PNAME
                        FROM BugFarmProjects
                        
                        COMMIT TRANSACTION
                    END TRY
                    BEGIN CATCH
                        ROLLBACK TRANSACTION;
                        
                        PRINT ERROR_MESSAGE();
                            
                    END CATCH;
                ";
            return $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);         
        }    
    }
    
    class Versions extends Db {
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function getVersionList(){
            $SQL = "
                    BEGIN TRY
                        BEGIN TRANSACTION
                        
                        SELECT VersionName
                        FROM BugFarmVersions
                        WHERE (PROJECT = (SELECT TOP 1 ID FROM BugFarmProjects))
                        
                        COMMIT TRANSACTION
                    END TRY
                    BEGIN CATCH
                        ROLLBACK TRANSACTION;
                        
                        PRINT ERROR_MESSAGE();
                            
                    END CATCH;
                ";
            return $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);     
        }
        
        //return all versions by issue id and link type
        public function getVersions($issueid, $type){
            $AFFECTVERSIONSQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    SELECT VersionName FROM BugFarmVersions
                    WHERE (ID in (SELECT VERSIONID FROM BugFarmIssueVersions WHERE ISSUEID = ? AND (LinkType = ?)))
                    
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
                ";
            $affectversions = '';
            $params = array($issueid, $type);
            $val = $this->find($AFFECTVERSIONSQL, $params, SQLSRV_FETCH_NUMERIC);    
            $j = 0;
            foreach ($val as $v) {
                if ($j == 0){
                    $affectversions = $v[0];    
                } else {
                    $affectversions = $affectversions . ', ' . $v[0];
                }
                $j++;         
            }
            return $affectversions;    
        }
            
    }      

   class Users extends Db {
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function getUserSession($userid){
            $SQL = "
                BEGIN TRY
                    BEGIN TRANSACTION 
                    
                    SELECT ID, UNAME, FIRST_NAME, LAST_NAME
                    FROM BugFarmUsers
                    WHERE (ID = ?);
                    
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                END CATCH;
        
            ";
            $params = array($userid);
            return $this->find($SQL, $params, SQLSRV_FETCH_ASSOC);    
        }
        
        public function validateUser($user_username, $user_password){           
            $SQL = "EXEC dbo.spValidateLogin
                    @uname = ?,
                    @userpw = ?";
            $params = array($user_username, $user_password);
            $val = $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);
            if (!empty($val)){
                return $val[0][0];
            } else {
                return -1;
            }
                    
        }
        
        public function addUser($params){
                $SQL = "EXEC dbo.spAddUser
                    @uname = ?, 
                    @fname = ?, 
                    @lname = ?, 
                    @userpw = ?, 
                    @email_address = ?
        ";
                $this->save($SQL, $params);    
        }
        
        //return list of users
        public function getUserList(){
            $SQL = "
                    BEGIN TRY
                        BEGIN TRANSACTION
                        
                        SELECT UNAME
                        FROM BugFarmUsers
                        WHERE (ACTIVE > 0)
                        
                        COMMIT TRANSACTION
                    END TRY
                    BEGIN CATCH
                        ROLLBACK TRANSACTION;
                        
                        PRINT ERROR_MESSAGE();
                            
                    END CATCH;
                ";
            return $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);    
        }
        
        //return any single users first and last name
        public function getUserFNLN($userid){
            $USERNAMESQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    SELECT FIRST_NAME, LAST_NAME FROM BugFarmUsers
                    WHERE ID = ? 
                                
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
            ";
            $uname = '';
            $params = array($userid);

            $val = $this->find($USERNAMESQL, $params, SQLSRV_FETCH_NUMERIC);
            
            foreach ($val as $v){
                $uname = $v[0] . ' ' . $v[1];
            }
            return $uname; 
        }     
   }
   
   class Issues extends Db{
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function getIssue($ikey) {
            $params = array($ikey);
            $SQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    SELECT ID, IssueType, IKey, Summary, ASSIGNEE, REPORTER, Priority, Status, Resolution, CREATED, UPDATED, Description 
                    FROM BugFarmIssues
                    Where Ikey = ?
                                
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
            ";
            return $this->find($SQL, $params, SQLSRV_FETCH_ASSOC);     
        }
        //(? + '-' + (CAST((SELECT IDENT_CURRENT('BugFarmIssues')) AS nvarchar))
        //create issue
        public function createIssue($params, $affectversions){
            $SQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    DECLARE @pname nvarchar(255)
                    SET @pname = ?
                    
                    INSERT INTO BugFarmIssues (IKey, PROJECT, REPORTER, ASSIGNEE, IssueType, Summary, [Description], [Priority], [Status], Resolution, CREATED, UPDATED)
                    VALUES
                    ((@pname + '-' + CAST((SELECT COUNT(PROJECT) FROM BugFarmIssues WHERE (PROJECT = (SELECT ID FROM BugFarmProjects WHERE PNAME = @pname))) + 1 AS nvarchar)),
                    (SELECT ID FROM BugFarmProjects WHERE PNAME = @pname),
                    (SELECT ID FROM BugFarmUsers WHERE UNAME = ?),
                    (SELECT ID FROM BugFarmUsers WHERE UNAME = ?),
                    ?,
                    ?,
                    ?,
                    ?,
                    'Open',
                    'Unresolved',
                    GETDATE(),
                    GETDATE()       
                    );";
             $SQLTWO = "";
             if ($affectversions != 'None selected' && $affectversions != ''){
                $varray = explode(',', $affectversions);
                $SQLTWO = "INSERT INTO BugFarmIssueVersions (ISSUEID, VERSIONID, LinkType)
                        VALUES";
                $i = 0;
                $len = count($varray);
                foreach($varray as $v){
                    $SQLTWO = $SQLTWO . 
                    "
                        (
                        (SELECT IDENT_CURRENT('BugFarmIssues')),
                        (SELECT ID FROM BugFarmVersions WHERE VersionName = ?),
                        'AffectVersion'
                        )        
                    ";
                    if ($i == $len - 1) {
                        $SQLTWO = $SQLTWO . ";";
                    } else {
                        $SQLTWO = $SQLTWO . ",";  
                    }
                    array_push($params, trim($v));
                    $i++;
                }
            }
            $SQLEND = "COMMIT TRANSACTION
                    END TRY
                    BEGIN CATCH
                        ROLLBACK TRANSACTION;
                        
                        PRINT ERROR_MESSAGE();
                            
                    END CATCH;
            ";
            $SQL = $SQL . $SQLTWO . $SQLEND;
            $this->save($SQL, $params);       
               
        }
        
        public function getIssueID($ikey){
            $params = array($ikey);
            $SQL= "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    SELECT ID 
                    FROM BugFarmIssues
                    Where Ikey = ?
                                
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
            ";
            return $this->find($SQL, $params, SQLSRV_FETCH_ASSOC);  
        }
        
        public function addComment($ikey, $comment, $user){
            $issueid = $this->getIssueID($ikey);
            $params = array($comment, $issueid[0]['ID'], $user);
            $SQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    INSERT INTO BugFarmIssueComments (Comment, IssueID, UserID)
                    VALUES
                    (?, ?, ?)
                    
                                
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
            ";
            $this->save($SQL, $params);    
        }
        
        public function getComment($ikey, $comment, $user){
            $params = array($ikey, $comment, $user);
            $SQL = "BEGIN TRY
                    BEGIN TRANSACTION
                    
                    select TOP 1 ID as commentid,
                            Comment as comment, 
						    (select FIRST_NAME + ' ' + LAST_NAME FROM BugFarmUsers WHERE ID = UserID) as [username], 
							Created as [date] FROM
					BugFarmIssueComments 
					WHERE
					(IssueID = (select ID from BugFarmIssues WHERE IKey = ?)) AND
                    (Comment = ?) AND
                    (UserID = ?)
                    ORDER BY ID DESC
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;";
          return $this->find($SQL, $params, SQLSRV_FETCH_ASSOC);    
        }
        
        public function getCommentList($ikey){
            $params = array($ikey);
            $SQL = "BEGIN TRY
                    BEGIN TRANSACTION
                    
                    select ID as commentid,
                            Comment as comment, 
						    (select FIRST_NAME + ' ' + LAST_NAME FROM BugFarmUsers WHERE ID = UserID) as [username], 
							Created as [date] FROM
					BugFarmIssueComments 
					WHERE
					(IssueID = (select ID from BugFarmIssues WHERE IKey = ?))
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;";
          return $this->find($SQL, $params, SQLSRV_FETCH_ASSOC);
                
        }
            
   }

   class Search extends Db{
        protected function find($SQL, $params, $fetch){
            return $this->query($SQL, $params, $fetch);      
        }
        protected function save($SQL, $params){
            $this->commit($SQL, $params);    
        }
        
        public function searchdb($searchterm, $offset, $fetch){
            $params = array($searchterm, $searchterm, $offset, $fetch);
            $SQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    SELECT ID, IssueType, IKey, Summary, ASSIGNEE, REPORTER, Priority, Status, Resolution, CREATED, UPDATED 
                    FROM BugFarmIssues
                    Where (Summary Like ('%' + ? + '%')) OR (Description Like ('%' + ? + '%'))
                    ORDER BY ID
                    OFFSET ? ROWS FETCH NEXT ? ROWS ONLY
                    
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
            ";
           return $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);    
        }
        
        public function countrows($searchterm){
            $params = array($searchterm, $searchterm);
            $SQL = "
                BEGIN TRY
                    BEGIN TRANSACTION
                    
                    SELECT count(ID)
                    FROM BugFarmIssues
                    Where (Summary Like ('%' + ? + '%')) OR (Description Like ('%' + ? + '%'))
                    
                    COMMIT TRANSACTION
                END TRY
                BEGIN CATCH
                    ROLLBACK TRANSACTION;
                    
                    PRINT ERROR_MESSAGE();
                        
                END CATCH;
            ";
            
            $val = $this->find($SQL, $params, SQLSRV_FETCH_NUMERIC);
            return $val[0][0];
                
        }
    }
  
  
  

?>