iTop allows for a lot of customization. Currently, our modules/extensions mainly make adjustments to the data model. You can choose any combination of our extensions.

Some tweaks to the data model are only available for the **ITIL**-compliant iTop.

Also take note of our Support Plan.

# Overview of our customization

Tweaks (changes/improvements)
* **Class: Change (ITIL) - don't automatically add impacted CI's (on insert/update)**
* **Class: Change (ITIL) - don't make private log read-only**
* **Class: NormalChange and RoutineChange (ITIL) - don't make title read-only**
* **Class: Functional CI - show all related tickets (not only active)**
* **Class: Incident Management (ITIL) - don't automatically add impacted CI's (on insert/update)**
* **Class: NormalChange and RoutineChange (ITIL) - reject anytime (before implement)**
* **Class: NormalChange and RoutineChange (ITIL) - don't make title read-only**
* **Class: UserRequest (ITIL) - don't automatically add impacted CI's (on insert/update)**
* **Class: UserRequest (ITIL) - don't make private log read-only (on close)**
* **Class: UserRequest (ITIL) - don't make title read-only (on resolve)**
* **Class: UserRequest (ITIL) - orgin: add 'in person'**
* **Class: UserRequest (ITIL) - public log after assign**
* **Class: UserRequest (ITIL) - remove resolution (on resolve)**

New classes
* **Class: IP Devices**
* **Class: Monitor**  

Features
* **Feature: Mail to Ticket Automation**
 

Details below.

# Installing
1. Place all folders with prefix **jb-** in # iTop/web/extensions
2. Make sure permissions of **iTop/web/conf/production/config-itop.php** are set to read/write
3. Browse your iTop installation and re-run the iTop installation/upgrade wizard. ( example http://itop/web/setup ).

# Upgrading
1. Remove any previous versions of my customizations (iTop/web/extensions, folders starting with prefix: jb-).
2. See release notes if they contain any additional steps.
3. Check steps for installation.


# Details of the extensions
## Tweaks 
### Class: Change (ITIL) - don't automatically add impacted CIs (on insert/update)
It's nice to pick any CI and see which changes may have caused a form of impact on it.
However, it's rather distracting and counterproductive to see all *potentially impacted* (computed) CI's when you view the change.

Say you add a firewall rule to open up a new port for one specific application. 
On adding the firewall rule, iTop adds everything related to the firewall to this user request.
To see a Change's complete *possible* impact, you can still view the *Impact Analysis* tab.


### Class: Functional CI - show all related tickets 
I think it used to be default behavior in iTop to show all related tickets for a CI. 
Anyhow, in my case I want to have quick access to the complete overview of tickets. 
This model change takes care of that, so you can for example easily see all issues related to a PC. 
 

### Class: Change (ITIL) - don't automatically add impacted CIs (on insert/update)
It's nice to pick any CI and see which changes may have caused a form of impact on it.
However, it's rather distracting and counterproductive to see all *potentially impacted* (computed) CI's when you view the change.

Say you add a firewall rule to open up a new port for one specific application. 
On adding the firewall rule, iTop adds everything related to the firewall to this user request.
To see a Change's complete *possible* impact, you can still view the *Impact Analysis* tab.
### Class: NormalChange and RoutineChange (ITIL) - reject anytime (before implement)
Allows a normal change to still be rejected in any phase prior to implementation.


### Class: Incident Management (ITIL) - don't automatically add impacted CIs (on insert/update)
It's nice to pick any CI and see which changes may have caused a form of impact on it.
However, it's rather distracting and counterproductive to see all *potentially impacted* (computed) CI's when you view the incident.

Say an ESXi-server goes down. You'd get everything related to it.
To see an Incident's complete *possible* impact, you can still view the *Impact Analysis* tab.

### Class: NormalChange and RoutineChange (ITIL) - don't make private log read-only
Title can always be changed.

### Class: NormalChange and RoutineChange (ITIL) - don't make title read-only
Title can always be changed.

### Class: User Request (ITIL) - don't automatically add impacted CIs (on insert/update)
It's nice to pick any CI and see which tickets may have caused a form of impact on it.
However, it's rather distracting and counterproductive to see all *potentially impacted* (computed) CI's when you view the user request.

Say you add a firewall rule to open up a new port for one specific application. 
On adding the firewall rule, iTop adds everything related to the firewall to this user request.
Sadly, contrary to a Change, the User Request does not have the *Impact Analysis* tab.


### Class: User Request (ITIL) - don't make title read-only (on resolve)
Tickets are quickly marked as resolved. This makes the title attribute read-only. 
If you still want to give the ticket a more meaningful title, you need to reopen the ticket. 
However, this may lead to users and agents receiving notifications (if configured). 

This change in the datamodel makes sure you can modify the title any time without consequences.


### Class: User Request (ITIL) - origin - add option 'in person'
When using an internal help desk, people still often drop by or ask you something when you're around their workstation.


### Class: User Request (ITIL) - public log after assign
Only show the public log after the ticket has been assigned. If you're working with mail notifications, 
iTop can only trigger the notification once the ticket has been created. Showing the public log in the creation step 
invites your helpdesk agents to immediately communicate with the end user - who never gets an email.


### Class: User Request (ITIL) - remove resolution (on resolve)
A mandatory solution and resolution code are asked by default. 
But we heavily rely on the private log to document our solutions.
In our case, it would often a copy-paste of the latest public log entry.
Putting a single character in the solution field doesn't add anything either.

 




## New classes

### Class: IP Devices  
Originated as an adjustment to IP Phones.
However, to make it more fitting for future purposes, I first added a class IP Device. 
It's very similar to a network device, but it's been defined at the same level as PC, Printer and DataCenter Device.
( Network Device is a subclass of DataCenter Device already ).

iTop by default considers IP Phones to be an element similar to regular phones. 
In my humble opinion, it lacks an essential Network Devices tab. 

This module redefines the IP Phone class to be a basic IP Device, 
with some additional properties: internal number and external number. 
This makes sense, because a lot of IP Phones are small switches - put between a PC and a network switch. 

It also adds an IP Camera class.

**Warning**: 
Please backup your existing IP Phones. It might be best to:
1. export your existing IP Phones to a CSV-file
2. delete them in iTop
3. install the extension,
3. adjust the CSV to the redefined IP Phone class 
4. import from CSV 

It's unknown to me if you'll keep related documents etc. 
I wasn't satisfied with the default IPPhone class, so I never used it and had no data to lose. 


### Monitor 
This is mostly a copy of iTop's "Add Sample Class" extension, which is used in a tutorial, extended with some fields (number of ports: VGA, HDMI, DisplayPort)

However, one important issue has been fixed. If you use the original example, you can create a new monitor CI. If you want to define a new model, you can do so, but there's no model type "Monitor". You could select a different model type such as "Peripheral". But, if you add another monitor CI, same brand, you notice the model you just added for the previous CI won't appear for you to select. On 9th of April 2018, a Combodo employee confirmed they will add my fix in the official version.

##  Features 

###  Mail to Ticket Automation
We extended the Combodo version with some additional options. See **Mail to Ticket.md**
