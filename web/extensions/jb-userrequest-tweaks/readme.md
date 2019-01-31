# jb-class-request-mgmt-itil-origins

## What?

Makes changes to UserRequest:
- Adds an 'in person', 'monitoring' origin to UserRequest. 
- Changes default impact to 'person' (more common) rather than 'department'
- Don't make 'title' and 'caller_id' read-only
- No mandatory solution (usually just in private log)
- Always lookup related Change, Incident, Problem
- At our organization, we use the 'Email Reply' extension, which allows you to send the contents of public log entries you write to the caller of a UserRequest. However, if you create a new ticket, I often find myself writing something to the user right away in the public log. That mail never arrives. So this extension prevents me from writing anything in the public log before the ticket is created.

