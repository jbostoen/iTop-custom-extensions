USE itop;

# Select most recent date
SET @mostRecent = ( 
	SELECT MAX(installed) 
    FROM priv_module_install 
);

DELETE FROM priv_module_install 
WHERE installed != @mostRecent;
 