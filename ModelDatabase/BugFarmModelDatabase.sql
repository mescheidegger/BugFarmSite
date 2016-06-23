DROP DATABASE BugFarm

CREATE DATABASE BugFarm
ON PRIMARY
(
	NAME = BugFarmData1,
	FileName = 'c:\Databases\BugFarm1.mdf',
	SIZE = 50MB,
	MAXSIZE = 1GB,
	FILEGROWTH = 10MB
)
LOG ON
(
	NAME = BugFarmLog1,
	FileName = 'c:\Databases\BugFarm1.ldf',
	SIZE = 1MB,
	MAXSIZE = 100MB,
	FILEGROWTH = 1MB
)

ALTER DATABASE BugFarm SET Recovery SIMPLE
USE BugFarm
GO

CREATE TABLE BugFarmUsers
(
	ID int IDENTITY (1,1) NOT NULL,
	CONSTRAINT pk_UserID PRIMARY KEY(ID),
	UNAME nvarchar(40),
	CONSTRAINT uc_Uname UNIQUE (UNAME), --Unique user names
	FIRST_NAME nvarchar(40) NOT NULL,
	LAST_NAME nvarchar(40) NOT NULL,
	PasswordHash Binary(64) NOT NULL,
	Salt UNIQUEIDENTIFIER,
	EMAIL_ADDRESS nvarchar(255) NOT NULL,
	ACTIVE bit NOT NULL DEFAULT 1,
	CREATED_DATE datetime NOT NULL DEFAULT GETDATE(),
	UPDATED_DATE datetime NOT NULL DEFAULT GETDATE()
)

CREATE TABLE BugFarmIssueTypes
(
	ID int NOT NULL identity(1,1) PRIMARY KEY, --Primary Key for issue list
	IType nvarchar(255), --Specify different issue types
	CONSTRAINT uc_IType UNIQUE (IType) --Issue types should be unique
)

INSERT INTO BugFarmIssueTypes --Populate table of issues
(IType)
VALUES
('New Feature'),
('Bug'),
('Improvement'),
('Task'),
('Question')

CREATE TABLE BugFarmPriorities
(
	ID int NOT NULL identity(1,1) PRIMARY KEY, --Primary Key for priorities
	PType nvarchar(255), --Specify different priorities
	CONSTRAINT uc_PType UNIQUE (PType) --Priority Types should be unique
)

INSERT INTO BugFarmPriorities --Populate table of priorities
(PType)
VALUES
('Minor'),
('Major'),
('Critical'),
('Blocker')

CREATE TABLE BugFarmResolutions
(
	ID int NOT NULL identity(1,1) PRIMARY KEY, --Primary Key for resolutions
	RType nvarchar(255), --Specify different resolutions
	CONSTRAINT uc_RType UNIQUE (RType) --Resolution Types should be unique
)

INSERT INTO BugFarmResolutions --Populate table of resolutions
(RType)
VALUES
('Fixed'),
('Wont Fix'),
('Duplicate'),
('Incomplete'),
('Cannot Reproduce'),
('Improper Use')

CREATE TABLE BugFarmStatuses
(
	ID int NOT NULL identity(1,1) PRIMARY KEY, --Primary Key for resolutions
	[Status] nvarchar(255), --Specify different resolutions
	CONSTRAINT uc_Status UNIQUE ([Status]) --Resolution Types should be unique
)

INSERT INTO BugFarmStatuses
([Status])
VALUES
('Open'),
('In Progress'),
('Resolved'),
('Tested'),
('Closed')

CREATE TABLE BugFarmProjects
(
	ID decimal(10,0) IDENTITY (1,1)  NOT NULL,
	CONSTRAINT pk_ProjectID PRIMARY KEY (ID), --Primary key for projects
	PNAME nvarchar(255), --project name
	PLEAD nvarchar(255), --User lead of project
	PLEADID int NOT NULL FOREIGN KEY (PLEADID) REFERENCES BugFarmUsers(ID), --Foreign Key to user ID in user table
	[DESCRIPTION] nvarchar(max) --Description Field for Project
)

CREATE TABLE BugFarmComponents --Describes different parts of a project 
(
	ID int IDENTITY(1,1) NOT NULL PRIMARY KEY,
	PROJECT decimal(10,0) NOT NULL FOREIGN KEY(PROJECT) REFERENCES BugFarmProjects(ID),
	ComponentName nvarchar(255),
	ComponentDescription nvarchar(max)
)

CREATE TABLE BugFarmProjectComponents --Projects can have multiple components applied to them
(
	ID int IDENTITY(1,1) NOT NULL PRIMARY KEY,
	PROJECT decimal(10,0) NOT NULL FOREIGN KEY(PROJECT) REFERENCES BugFarmProjects(ID),
	COMPONENT int NOT NULL FOREIGN KEY(COMPONENT) REFERENCES BugFarmComponents(ID),
	LinkType nvarchar(255)
)

CREATE TABLE BugFarmIssues
(
	ID int IDENTITY (1,1) NOT NULL,
	CONSTRAINT pk_IssueID PRIMARY KEY (ID), --primary key for issues
	IKey nvarchar(255),
	CONSTRAINT uc_IssueKey UNIQUE (IKey), --Unique name for issue
	PROJECT decimal(10,0) FOREIGN KEY (PROJECT) REFERENCES BugFarmProjects(ID), --Foreign Key to Project Table
	REPORTER int NOT NULL FOREIGN KEY (REPORTER) REFERENCES BugFarmUsers(ID),--Foreign Key to Users Table
	ASSIGNEE int NOT NULL FOREIGN KEY (ASSIGNEE) REFERENCES BugFarmUsers(ID),--Foreign Key to Users Table
	IssueType nvarchar(255) NOT NULL, --Issue type populated from BugFarmIssueTypes
	Summary nvarchar(255), --Title of the issue
	[Description] nvarchar(max), --Description of the issue can be large
	[Priority] nvarchar(255), --Priority populated from BugFarmPriorities
	Resolution nvarchar(255), --Resolution for an issue from BugFarmResolutions
	[Status] nvarchar(255), --Status of the issue from BugFarmStatuses
	CREATED datetime NOT NULL DEFAULT GETDATE(), --Default created date to current date time
	UPDATED datetime NOT NULL DEFAULT GETDATE(),
	RESOLUTIONDATE datetime
)

CREATE TABLE BugFarmIssueComments
(
	ID int IDENTITY (1,1) NOT NULL,
	CONSTRAINT pk_CommentID PRIMARY KEY (ID), --Primary key for comments
	Comment nvarchar(max) NOT NULL,
	IssueID int NOT NULL FOREIGN KEY (ISSUEID) REFERENCES BugFarmIssues(ID), --Foreign key to the issue
	UserID int NOT NULL FOREIGN KEY (USERID) REFERENCES BugFarmUsers(ID), --Foreign key to the user adding the comment
	CREATED datetime NOT NULL DEFAULT GETDATE()
)

CREATE TABLE BugFarmVersions
(
	ID int IDENTITY (1,1) NOT NULL,
	CONSTRAINT pk_VersionID PRIMARY KEY (ID), --Primary Key for versions
	PROJECT decimal(10,0) FOREIGN KEY (PROJECT) REFERENCES BugFarmProjects(ID),
	VersionName nvarchar(255),
	Descrption nvarchar(255),
	RELEASED bit NOT NULL DEFAULT 0, --True/False for RELEASED 0 default to false
	ARCHIVED bit NOT NULL DEFAULT 0, --True/False for Archived 0 default to false
	RELEASEDATE datetime
)

CREATE TABLE BugFarmIssueVersions --Use this table to create one to many relationships between Issues with multiple fix versions.
(
	ID int IDENTITY (1,1) NOT NULL PRIMARY KEY,
	ISSUEID int NOT NULL FOREIGN KEY (ISSUEID) REFERENCES BugFarmIssues(ID),
	VERSIONID int NOT NULL FOREIGN KEY (VERSIONID) REFERENCES BugFarmVersions(ID),
	LinkType nvarchar(255) -- The relationship type between Issue or Project and Component or Version 
)

CREATE PROCEDURE dbo.spAddUser
	@uname nvarchar(40), 
	@fname nvarchar(40), 
	@lname nvarchar(40), 
	@userpw nvarchar(50), 
	@email_address nvarchar(255)
AS
BEGIN TRY
	SET NOCOUNT ON
	
	BEGIN TRANSACTION
		DECLARE @salt UNIQUEIDENTIFIER=NEWID()

		INSERT INTO BugFarmUsers(UNAME, FIRST_NAME, LAST_NAME, PasswordHash, Salt, EMAIL_ADDRESS)
		VALUES(@uname, @fname, @lname, HASHBYTES('SHA2_512', @userpw+CAST(@salt AS NVARCHAR(36))), @salt, @email_address)
	
	COMMIT TRANSACTION
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION;

	PRINT ERROR_MESSAGE();
END CATCH;

CREATE PROCEDURE dbo.spValidateLogin
	@uname nvarchar(255),
	@userpw nvarchar(50)
AS
BEGIN TRY
	SET NOCOUNT ON
	BEGIN TRANSACTION
	
		IF EXISTS (SELECT TOP 1 ID From BugFarmUsers WHERE UNAME = @uname)
		BEGIN
			SELECT TOP 1 ID FROM BugFarmUsers
			WHERE (UNAME = @uname) AND (PasswordHash=HASHBYTES('SHA2_512', @userpw+CAST(Salt AS NVARCHAR(36))))
		END

	COMMIT TRANSACTION
END TRY
BEGIN CATCH
	ROLLBACK TRANSACTION;

	PRINT ERROR_MESSAGE();
END CATCH;
