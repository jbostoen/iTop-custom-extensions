# jb-geom

## What?
Geometry tab - Adds a geometry field (tab with OpenLayers Map) to specified classes. The field is named 'geom'.

## Cookbook
XML: 
* adds a new field 'geom' to some classes (Location, UserRequest, ...)

PHP: 
* shows how to work with iApplicationUIExtension::OnDisplayRelations()
* shows how to add menus with iPopupMenuExtension::EnumItems()
* how to obtain a list of all iTop attribute names to check if an attribute is present ('geom')
* how to add a CSS style, a CSS stylesheet, additional JavaScript libraries, JavaScript and HTML code
* ...

# Configuration
In the iTop configuration, you can find settings to adjust these options.
It's possible to specify both defaults or class specific settings.

* dataformat: String. Features are stored in this format. Allowed values: 'GeoJSON', 'WKT'
* datacrs: String. Features are stored in this CRS. Often 'EPSG:3857' (CRS used by Google Maps, OpenStreetMap WMS) or 'EPSG:4326' (GeoJSON. CRS used by Google Earth, OpenStreetMap database)
* datatypes: Array of strings. Specifies which geometry types can be drawn. Only SinglePoint features supported right now (no MultiPoint). Allowed values: 'Point', 'LineString', 'Polygon'
* mapcrs: String. Specifies the CRS in which the map is displayed. Example: 'EPSG:3857'
* mapcenter: Array of coordinates. Centers the map to this point, if no feature has been drawn yet (otherwise it will center to the feature).
* mapzoom: Integer. Zoom level.

# Limitations
* currently 1 feature per object
* if you have really complex features with lots of points, it may be too large to store. Probably won't happen.
* currently stored as an AttributeString (so not a MySQL geometry field).

# Good to know
* Using QGIS Desktop (open source and free), you can easily visualize the data and do all sorts of geospatial analyses.
