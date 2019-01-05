

# Methods of iApplicationObjectExtension 
You can use a custom class and implement the interface above.
You must define all methods:

- OnCheckToDelete() (return empty array or array containing string with error messages)
- OnCheckToWrite() (return empty array or array containing string with error messages)
- OnIsModified() (return Boolean - true if anything has changed)

These methods are used AFTER the action, not BEFORE.
- OnDBInsert()
- OnDBUpdate()
- OnDBDelete()


# Using $this->Get('id')
Id will usually return the id, but for new objects there seem to be (at least?) two cases in iTop 2.5.0 where the ID is different:
- when calling OnCheckToWrite(), ID was empty
- when creating it from a menu link ('new FunctionalCI') where you actually see the entire page with details: id is -1 for new objects


