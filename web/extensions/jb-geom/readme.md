# jb-geom

## Special note
This was complex to develop and it is very feature rich, so this is now a professional extension.
If you want to use this extension and get support, get in touch please: jbostoen.itop@outlook.com


## What?
Adds a geometry to specified classes. 

This extension relies on the presence of an attribute named 'geom' in the class.
It can be added by minimally modifying the datamodel.

Objects with this attribute will have a geometry tab which shows a map with the feature (Point, LineString, Polygon).
Adding, modifying or clearing the feature from the object details is very straight forward.

* last used basemap (background map, such as OpenStreetMap) is remembered per user for each class.
* iTop Config file: 
  * set defaults for all maps (zoom, map center, which type of features are allowed, source and target CRS, ...)
  * set more specific defaults per class

There's also a **dashlet** to show all objects of a class on a map.

**Dashlet features**
* search form with a look similar to iTop's native search for objects
* a feature can be clicked upon to see its details in a popup. Customizable which attributes (text-based only) are shown.
* if a feature is clicked on while holding the [Alt]-key, iTop immediately redirects to the object details page
* if enabled, clicking on an empty spot on the map will result in redirection to a new object creation page with geometry (Point) prefilled.
* if enabled, objects are clustered on zooming out. Hovering over the cluster gives a sneak peak of the clustered features.
* last used basemap is remember per user and per dashlet


## Screenshots
![Object details](docs/20190711_object_details.PNG)
![Dashlet](docs/20190711_dashlet.PNG)
![Clustering and basemap](docs/20190824_basemap_and_clustering.PNG)

## Cookbook
XML: 
* adds a new field 'geom' to some classes (Location, UserRequest, ...)

PHP: 
* how to work with iApplicationUIExtension::OnDisplayRelations()
* how to add menus with iPopupMenuExtension::EnumItems()
* how to get current module settings (utils::GetCurrentModuleName(), utils::GetCurrentModuleDir())
* how to obtain a list of all iTop attribute names to check if an attribute is present ('geom')
* how to get the label (translation) of attributes
* how to add a CSS style, a CSS stylesheet, additional JavaScript libraries, JavaScript and HTML code
* how to create a custom dashlet
* how to hide a field
* ...

JS:
* copy object with its geometry to GeoJSON (clipboard)

iTop internals
* how to redirect to iTop new object creation page AND prefill information


# Configuration
In the iTop configuration, you can find settings to adjust these options.
It's possible to specify both defaults or class specific settings.

* **dataformat**: String. Features are stored in this format. Allowed values: 'GeoJSON', 'WKT' (highly recommended: WKT)
* **datacrs**: String. Features are stored in this CRS. Often 'EPSG:3857' (CRS used by Google Maps, OpenStreetMap WMS) or 'EPSG:4326' (GeoJSON. CRS used by Google Earth, OpenStreetMap database)
* **datatypes**: Array of one or more strings. Specifies which geometry types can be drawn. Only SinglePoint features supported right now (no MultiPoint). Allowed values: 'Point', 'LineString', 'Polygon'
* **mapcrs**: String. Specifies the CRS in which the map is displayed. Example: 'EPSG:3857'
* **mapcenter**: Array of coordinates. Centers the map to this point, if no feature has been drawn yet (otherwise it will center to the feature).
* **mapzoom**: Integer. Zoom level.

A cookie remembers the last chosen basemap for 30 days - per user, per class or dashlet.

# Limitations
* currently 1 feature per object. It has to be a SinglePoint, SingleLineString or SinglePolygon. MultiPoint, MultiLineString, MultiPolygon or other geometries are unsupported.
* if you have really complex features with lots of points, it may be too large to store. Probably won't happen.
* currently stored as an AttributeString (so not a MySQL geometry field).


# Good to know
* Using QGIS Desktop (open source and free), you can easily visualize the data and do all sorts of geospatial analyses.

# Roadmap
* Custom WMS in iTop config
* Search bar

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen

