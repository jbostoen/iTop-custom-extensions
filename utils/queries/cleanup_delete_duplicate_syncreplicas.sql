# If you mistakenly used INSERT IGNORE or INSERT ... ON DUPLICATE KEY on the synchro data source tables,
# you might have a lot of replicas now.
# This will help you to clean them ip.

SET @itopDataSourceId = 21; # Set your own data source ID

# There can be multiple dest_classes per sync_source_id.
# For now it's unknown why.
# We create this temporary table for the delete-query, which can't reference itself
CREATE TABLE tmp_delete_duplicate_syncs AS 
SELECT MIN(id) AS minId FROM priv_sync_replica 
WHERE sync_source_id = @itopDataSourceId 
GROUP BY sync_source_id, dest_class, dest_id ;

# Now delete all newer records
DELETE FROM priv_sync_replica 
WHERE sync_source_id = @iTopDataSourceId 
AND id NOT IN ( SELECT minId FROM tmp_delete_duplicate_syncs  );

# Drop temporary table
DROP TABLE tmp_delete_duplicate_syncs;
