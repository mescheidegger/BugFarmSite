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