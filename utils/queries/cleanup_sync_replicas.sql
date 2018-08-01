# NOT maintained, here for reference. Actually from experience I'd recommend deleting and re-creating the Data Sync Source in iTop.

# If you mistakenly used INSERT IGNORE or INSERT ... ON DUPLICATE KEY on the synchro data source tables,
# you might have a lot of replicas now. This will help you to clean them up.

# Be aware that you may need to adjust the timeout!
# SET SESSION wait_timeout = 300; # in seconds. From a test case, 2 million records take more than 5000 seconds!

# Use correct database

USE itop;
# Create procedure
DELIMITER //
CREATE PROCEDURE sp_cleanupSynchro (IN iTopDataSourceId INT)
BEGIN 
	# There can be multiple dest_classes per sync_source_id.
	# For now it's unknown why.
	# We create this temporary table for the delete-query, which can't reference itself
	CREATE TABLE tmp_delete_duplicate_syncs AS 
	SELECT MIN(id) AS minId FROM priv_sync_replica 
	WHERE sync_source_id = iTopDataSourceId 
	GROUP BY sync_source_id, dest_class, dest_id ;
	# Now delete all newer records
	DELETE FROM priv_sync_replica 
	WHERE sync_source_id = iTopDataSourceId 
	AND id NOT IN ( SELECT minId FROM tmp_delete_duplicate_syncs );
	# Drop temporary table
	DROP TABLE tmp_delete_duplicate_syncs;
END //
# Reset delimiter
DELIMITER ;
# Now for each data source, call:
FOREACH ( SELECT id FROM priv_sync_datasource ) 
	CALL sp_cleanupSynchro( id );
ENDFOREACH
# Drop procedure
DROP PROCEDURE sp_cleanupSynchro;
