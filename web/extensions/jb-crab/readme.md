# jb-crab

## What?
CRAB is an official Flemish open data source. It's a dataset which contains all official addresses in Flanders.

This extension brings CrabStreet and CrabAddress classes.

There's also a (currently) standalone script to import that data into iTop, so you can link Crab Addresses to certain classes.

This extension requires jb-geom.

## Cookbook

XML:
- create new classes CrabStreet and CrabAddress

PHP:
- make sure ContactMethod follows certain rules. Warning if necessary, strip unnecessary parts where needed

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen
