# jb-crab

An attempt to import addresses from a GeoJSON file into iTop.

The GeoJSON file is actually downloaded from the open data from the Flemish government. 

Ironically, downloading a subset is only possible if you'd be authenticated. 
It's easier to download the whole package without authenticating and then deriving the subset. 
You can download a shapefile; which can be converted to a GeoJSON file. 
CSV gave bad results; and the WSDL-service "CRAB READ" was also not practical for our use case.

The address list is then transformed into two classes ( CrabStreet, CrabAddress ).

# Cookbook
- use the friendlyname attribute of another class in the naming field
