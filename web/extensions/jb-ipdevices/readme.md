# jb-ipdevices

Careful: this extension overrules the original iTop IPPhone class.
Make sure you don't have IP Phones as defined by the default iTop data model; data may get lost!

This extension is not recommended for beginners. For them, I recommend listing your IPPhones as NetworkDevice instead and give it their own type.

## What?
This contains a number of new classes.

* **IPDevice**: abstract class, parent for various subclasses of IP Devices.
* **IPCamera**: a more specific kind of IP Device
* **IPDeviceGeneric**: generic class for all sorts of devices. Examples: smart home and home automation systems, HVAC, sensors, ...
* **IPPhone**: the most interesting one, because iTop already has an IP Phone. In my humble opinion, it's totally wrong in Combodo's data model. The IP Phone is seen as a phone, instead of a network device ( ConnectableCI ). At my employer, the phones are also switches - they're in between the PC and a regular switch. 
* **lnkConnectableCIToConnectableCI**: a new link class; which replaces lnkConnectableCIToNetworkDevice (it doesn't remove this old class though!)

### About lnkConnectableCIToConnectableCI
This is mostly a copy of lnkConnectableCIToNetworkDevice, but needed because IPDevices are children of ConnectableCI, NOT NetworkDevice.

Therefore, there are modifications to ConnectableCI and NetworkDevice as well.

Mind this while installing! Manual operations may be needed in the SQL database!
- ConnectableCI
- NetworkDevice

It also adds a 'patch label' field.

With some work (for example CSV export/import), you can copy most of your old data from the lnkConnectableCIToNetworkDevice to lnkConnectableCIToConnectableCI.

## Cookbook

XML
- define new classes
- change attributes of existing classes

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
