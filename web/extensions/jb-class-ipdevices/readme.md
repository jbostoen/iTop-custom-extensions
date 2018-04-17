# jb-class-ipdevices
Careful: My extension overrules the original iTop IP Phone class.
Make sure you don't have IP Phones as defined by the default iTop data model.

This contains 3 new classes.
* **IP Device** : generic class for all sorts of devices (all sorts of sensors etc. Think domotica systems)
* **IP Camera** : a more specific kind of IP Device
* **IP Phone** : the most interesting one, because iTop already has an IP Phone. In my humble opinion, it's totally wrong in Combodo's data model. The IP Phone is seen as a phone, instead of a network device ( ConnectableCI ). At my employer, the phones are also switches - they're in between the PC and a regular switch. 

