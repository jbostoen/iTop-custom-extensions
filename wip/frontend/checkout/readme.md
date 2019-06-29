
**You need to get a copy of Twig to use this**

This is a quick experiment with the iTop REST WebServices, it can definitely use some more tweaking.

Basically, it's a check-in and check-out service. 
It relies on the LendRecord class created by my colleague jdefoort ( https://github.com/jdefoort/iTopExtensions ).

This is an easy user interface for someone who's in charge of PhysicalDevices (we extended this with Tools, Vehicles, but it could be lending any sort of PhysicalDevice).
The person in charge of the PhysicalDevices can simply register incoming and outgoing physical devices with a barcode scanner (based on serial number). 
This person then selects who's bringing/taking those items and clicks 'register'.

With iTop REST WebServices, everything's recorded through iTop in a new LendRecord class (basically: org_id, contact_id, physicaldevice_id, date_out, date_in, reason, remark).

