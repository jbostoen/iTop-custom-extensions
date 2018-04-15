use itop;
 
 
/*
	Which outdated files can safely be removed, based on their extensions? 
	Do this for graphics and most common Office files. 
	Don't do this for .cfg, .xml (those files are small and usually config files, so previous versions is nice in this case. 
*/

    
/* Delete all change entries. */ 
DELETE FROM priv_change 
WHERE 
	id IN ( 
		SELECT DISTINCT(changeid) 
        FROM priv_changeop 
        WHERE id IN ( 
			/* --- Subselection: select Change operation IDs to be deleted (only changes related to actual document file changes) --- */
			(

				SELECT priv_changeop.id 
				FROM priv_changeop  
				LEFT JOIN priv_changeop_setatt_data ON priv_changeop.id = priv_changeop_setatt_data.id
				WHERE 
							objclass LIKE 'DocumentFile' 
					AND 	optype = 'CMDBChangeOpSetAttributeBlob' 
					AND 	
							( 
									RIGHT(LCASE(prevdata_filename), 4 ) IN ( '.doc', '.jpg', '.pdf', '.png', '.ppt', '.xls' )
								OR 	RIGHT(LCASE(prevdata_filename), 5 ) IN ( '.docx', '.xlsx', '.pptx' )
							)
					
			)
			/* --- End subselection --- */
		) 
	);
 
# In this case, delete from priv_changeop; priv_changeop_set_blob and priv_changeop_set_data
DELETE FROM priv_changeop_setatt
WHERE 
	id IN ( 
		/* --- Subselection: select Change operation IDs to be deleted (only changes related to actual document file changes) --- */
		(

			SELECT priv_changeop.id 
			FROM priv_changeop  
			LEFT JOIN priv_changeop_setatt_data ON priv_changeop.id = priv_changeop_setatt_data.id
			WHERE 
						objclass LIKE 'DocumentFile' 
				AND 	optype = 'CMDBChangeOpSetAttributeBlob' 
				AND 	
						( 
								RIGHT(LCASE(prevdata_filename), 4 ) IN ( '.doc', '.jpg', '.pdf', '.png' )
							OR 	RIGHT(LCASE(prevdata_filename), 5 ) IN ( '.docx' )
						)
				
		)
		/* --- End subselection --- */
	);
    
/* Remove from priv_changeop_setatt_data (blob data of document), priv_changeop (type of change op) */
DELETE priv_changeop_setatt_data, priv_changeop     
FROM priv_changeop_setatt_data
LEFT JOIN priv_changeop   ON  priv_changeop_setatt_data.id =  priv_changeop.id
WHERE 
			priv_changeop.objclass LIKE 'DocumentFile' 
	AND 	priv_changeop.optype = 'CMDBChangeOpSetAttributeBlob' 
	AND 	
		( 
				RIGHT(LCASE(priv_changeop_setatt_data.prevdata_filename), 4 ) IN ( '.doc', '.jpg', '.pdf', '.png' )
			OR 	RIGHT(LCASE(priv_changeop_setatt_data.prevdata_filename), 5 ) IN ( '.docx' )
		)
;


	
    
    