
# Best practices


## Contracts

Point of view:
* provider contracts = services you get
* customer contracts = services you deliver



## Documents

Revisions (history of versions):
iTop keeps a history! So if you keep configs in iTop, you can simply edit an existing document and upload the latest version rather than creating a new document! 




## Functional CI

### IP Phone
The Combodo version doesn't allow you to link an IP Phone (often a switch at the same time) to network devices and clients. Either use the Network Device or use a customized data model.

 

# Linking devices

**PC <=> switch:**
* Point of view from PC: downlink from switch. Network port = belongs to switch, device port = belongs to device (PC)
* Point of view from Network Device: downlink to PC. Network port = belongs to switch, device port = belongs to device (PC)

**switch <=> switch:**
* CI switch (Network Device): uplink. Network port = from regular switch, device port = belongs to other switch



# Triggers
## Notifications, triggers: States
A state is really a phase in the lifecycle. 'on entering/leaving a state' should be seen as: ticket is assigned, reassigned, closed, resolved. There's currently no standard implementation in iTop to send a notification on changing an attribute (e.g. server = inactive).


# Themes
* css -> contains css-variables.scss and light-grey.scss, but most colors are still 'hardcoded' rather than using a variable.  
