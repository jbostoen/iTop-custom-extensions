USE itop;

/* Select most recent date */
SET @mostRecentModule = ( 
	SELECT MAX(installed) 
    FROM priv_module_install 
);

/* Remove specific changes. Don't remove actual change, since that's most likely an iTop install/upgrade */
DELETE priv_module_install , priv_changeop , priv_changeop_create 
FROM priv_module_install 
LEFT JOIN priv_changeop ON priv_module_install.id = priv_changeop.objkey AND priv_changeop.objclass = 'ModuleInstallation' 
LEFT JOIN priv_changeop_create ON priv_changeop.id = priv_changeop_create.id 
WHERE priv_module_install.installed < @mostRecentModule;
 
 
 
SET @mostRecentExtension = ( 
	SELECT MAX(installed) 
    FROM priv_extension_install 
);

/* Remove specific changes. Don't remove actual change, since that's most likely an iTop install/upgrade */
DELETE priv_extension_install , priv_changeop , priv_changeop_create 
FROM priv_extension_install 
LEFT JOIN priv_changeop ON priv_extension_install.id = priv_changeop.objkey AND priv_changeop.objclass = 'ExtensionInstallation' 
LEFT JOIN priv_changeop_create ON priv_changeop.id = priv_changeop_create.id 
WHERE priv_extension_install.installed < @mostRecentExtension;
  