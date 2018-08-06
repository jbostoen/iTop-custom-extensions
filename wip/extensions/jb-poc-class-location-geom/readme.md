# Test: OpenLayers integration

Attempt to intregrate OpenLayers into iTop.

For now, one feature per item only.

Recommended settings are already set in the datamodel.

Short to-do list:
- either define on class level which geometry types are allowed; or at extension level; or no limitation (freestyle!)

Out of scope:
- adding a real Geometry field to iTop. According to a topic, it requires a lot of editing of the core code. Which makes it difficult to maintain. So let's stick to using a Text field and WKT/GeoJSON

This extension contains examples of:
* adding a completely new tab to a Class (DisplayBareRelations method) with references to CSS and JavaScript files
* commented out, but present: example on how to add an item to the 'Other actions' menu when viewing details
* using default module-parameters in the XML datamodel

