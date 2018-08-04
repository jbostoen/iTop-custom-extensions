# Test: OpenLayers integration

Attempt to intregrate OpenLayers into iTop.

For now, viewing of features only.

Short to-do list:
- Points work already. What about Polygons and LineStrings (the latter being least useful)?
-- eiter define on class level which geometry types are allowed; or at extension level; or no limitation (freestyle!)
- see if we can somehow make the geom attribute read-only to the user (native iTop code? or dirty jQuery hack?) - attribute must still be editable
- add module settings for format (WKT, GeoJSON), CRS to save in, CRS for map (recommended: EPSG:3857 - 4326 distorts but is on the other hand used in Google Maps-links), default map center, default map zoom

Out of scope:
- adding a real Geometry field to iTop. According to a topic, it requires a lot of editing of the core code. Which makes it difficult to maintain. So let's stick to using a Text field and WKT/GeoJSON

