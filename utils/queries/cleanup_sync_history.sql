
# Not tested yet, work in progress!
# Careful: there can be multiple change operations per change. 
# For names of CMDB Change Operations, see data model (menu)

START TRANSACTION;


USE itop;


# Delete syncs setting custom fields (untested)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_custfields.*,  priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_custfields ON priv_changeop.id = priv_changeop_setatt_custfields.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeCustomFields';


# Delete syncs setting data (used for file content) (tested)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_data.*,  priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_data ON priv_changeop.id = priv_changeop_setatt_data.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeBlob';


# Delete syncs setting encrypted (untested)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_encrypted.*,  priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_encrypted ON priv_changeop.id = priv_changeop_setatt_encrypted.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeEncrypted';


# Delete syncs setting password (untested)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_pwd.*,  priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_pwd ON priv_changeop.id = priv_changeop_setatt_pwd.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeOneWayPassword';


# Delete syncs setting HTML (sub class of longtext) (tested)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_longtext.*, priv_changeop_setatt_html.*, priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_longtext ON priv_changeop.id = priv_changeop_setatt_longtext.id 
LEFT JOIN priv_changeop_setatt_html ON priv_changeop.id = priv_changeop_setatt_html.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeHTML';


# Delete syncs setting longtext (advised: remove data from subclasses first) (tested)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_longtext.*, priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_longtext ON priv_changeop.id = priv_changeop_setatt_longtext.id  
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeLongText';


# Delete syncs setting log (untested! Does iTop just keep track of entry number, since you always append to a log?)
DELETE priv_changeop_setatt.*, priv_changeop_setatt_log.*, priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_log ON priv_changeop.id = priv_changeop_setatt_log.id  
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeLog';


# Delete syncs setting text
DELETE priv_changeop_setatt.*, priv_changeop_setatt_text.*, priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_text ON priv_changeop.id = priv_changeop_setatt_text.id  
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeText';


# Delete syncs setting scalar attributes
DELETE priv_changeop_setatt.*, priv_changeop_setatt_scalar.*,  priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_scalar ON priv_changeop.id = priv_changeop_setatt_scalar.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeScalar';


# Delete syncs setting URL attributes
DELETE priv_changeop_setatt.*, priv_changeop_setatt_url.*,  priv_changeop.* 
FROM priv_changeop 
LEFT JOIN priv_change ON priv_changeop.changeid = priv_change.id  
LEFT JOIN priv_changeop_setatt ON priv_changeop.id = priv_changeop_setatt.id 
LEFT JOIN priv_changeop_setatt_url ON priv_changeop.id = priv_changeop_setatt_url.id 
WHERE priv_change.origin = 'synchro-data-source' AND priv_changeop.optype = 'CMDBChangeOpSetAttributeURL';



# Now check if there are changes without any change operations. 
DELETE priv_change.* 
FROM priv_change 
LEFT JOIN priv_changeop ON priv_change.id = priv_changeop.changeid 
WHERE priv_changeop.changeid IS NULL;

 
# change to commit if okay. 
ROLLBACK;
