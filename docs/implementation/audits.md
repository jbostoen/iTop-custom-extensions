
# How-to: find software in Software Catalog without any instances
Credits: vdumas
* Create an Audit Category, with 'Definition Set' = "SELECT Software"
* Add an Audit Rule with 'Query to Run': "SELECT Software AS s JOIN SoftwareInstance AS si ON si.software_id = s.id"
* Set 'Valid objects?' to 'true'

* When auditing, it will report software with NO instances as an error.
 
