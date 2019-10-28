# jb-crab

## What?
CRAB is an official Flemish open data source. It's a dataset which contains all official addresses in Flanders.

This extension brings CrabStreet and CrabAddress classes.

There's also a (currently) standalone script to import that data into iTop, so you can link Crab Addresses to certain classes.

## Important notes
* This extension requires jb-geom.
* The cron job may disturb other cron jobs, since it runs for a long time because it downloads a large file and processes the dataset. Schedule at night.

## Cookbook

XML:
- create new classes: CrabCity, CrabStreet and CrabAddress


PHP:
- how to implement a cron job process in iTop (iScheduledProcess)
- using DBObjectSearch and DBObjectSet to fetch data

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
