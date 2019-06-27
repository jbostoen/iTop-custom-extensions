# Sync CRAB

## What?
Imports Flemish Crab Addresses using a specified filter (example included for a real life use case; adjust for your own purposes).

Should be implemented in the jb-crab extension though.

## Requirements
* iTop_Rest class (found in this repository)
* OGR2OGR (https://www.gdal.org/ogr2ogr.html)
* This folder must be put under iTop folder/cron
* Relies on open data provided by Flemish Government

## Good to know
Prior to this method of fetching a ShapeFile (open data provided by Flanders); there was a concept using the WS-CRAB Service instead. 
It turned out that service was too limited: having to fetch addresses one by one to see if it contained sub addresses. 
Hence the new approach downloads a (massive!) shapefile and processes it if OGR is installed.
