Quote vdumas:
Does the remaing InLineImage still have an object_id ?
There is a config parameter which describes how long an inline image without associated object is kept in DB. 
This is implemented because the image can be created while the object creation is never submitted. 
There is a background task called by the cron.php which does this clean-up.
