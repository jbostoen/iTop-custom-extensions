

# Indexes
Quote from iTop developers: 
Since iTop 2.5, all indexes that are not defined in the XML datamodel are deleted during setup. 
So in order to keep yours, you will have to make an extension that will add them.

# Insert statements
It's forbidden to use INSERT ... ON DUPLICATE KEY UPDATE and INSERT IGNORE statements, because iTop relies on MySQL triggers for the INSERT-command. The UPDATE-command is just fine though.
