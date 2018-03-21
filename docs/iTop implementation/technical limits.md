

# Technical Limits
1) PHP: **upload_max_filesize** (http://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize) The maximum size of the uploaded files. Any file bigger than this limit will be rejected by PHP. Default value: 2MB
2) PHP: **post_max_size** (http://www.php.net/manual/en/ini.core.php#ini.post-max-size) The maximum size of an HTTP POST request accepted by PHP. This value must be larger than upload_max_filesize since files are POSTed and the form used to upload the file also contains some other data (i.e. the other fields of the form).
3) PHP: **memory_limit** (http://www.php.net/manual/en/ini.core.php#ini.memory-limit) All the uploaded data end-up in memory at some point of time, so this value must be larger than post_max_size. Minimum value for running iTop: 32MB
4) MySQL: **max_allowed_packet** (http://dev.mysql.com/doc/refman/5.1/en/server-system-variables.html#sysvar_max_allowed_packet) The files are loaded as BLOBs into the database: one query is issued to insert the record containing the BLOB. Therefore the limit for the size of a MySQL query must be larger that the size of the biggest file you plan to upload. 

 
