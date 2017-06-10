use itop;
 
 
# Which outdated files can safely be removed, based on their extensions? 
set @ext3 = ('.doc', '.jpg', '.pdf', '.png'); 
set @ext4 = ('.docx');

 
# Select IDs of changes related to DocumentFiles. Based on extensions of previous files.
set @deleteChangeOps = (

	SELECT priv_changeop.id 
	FROM priv_changeop  
	LEFT JOIN priv_changeop_setatt_data ON priv_changeop.id = priv_changeop_setatt_data.id
	WHERE 
				objclass LIKE 'DocumentFile' 
        AND 	optype = 'CMDBChangeOpSetAttributeBlob' 
		AND 	
				( 
						RIGHT(LCASE(prevdata_filename), 4 ) IN ( @ext3 )
					OR 	RIGHT(LCASE(prevdata_filename), 4 ) IN ( @ext4 )
				)
        
);

# We got the IDs we wanted. 
# Delete all change entries. 
DELETE FROM priv_change 
WHERE 
	id IN ( 
		SELECT DISTINCT(changeid) 
        FROM priv_changeop 
        WHERE id IN ( @deleteChangeOps ) 
	);
 
# In this case, delete from priv_changeop; priv_changeop_set_blob and priv_changeop_set_data
DELETE FROM priv_changeop_setatt
WHERE 
	id IN ( @deleteChangeOps );
    
DELETE FROM priv_changeop_setatt_data 
WHERE 
	id IN ( @deleteChangeOps );
    
    