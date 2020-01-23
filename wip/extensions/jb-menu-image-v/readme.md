# jb-menu-image-v

## What?
Adds link to Image-V (mobile mapping) in Other Actions menu.

Only if 'geom' field is set or if 'crab_address_id' is set (and contains geometry).


## Cookbook

XML:
- nothing

PHP:
- iPopupMenuExtension::EnumItems() to add a popup menu which calls a JavaScript function (no direct access to WebPage object)
- iApplicationUIExtension::OnDisplayProperties() to add some additional JavaScript code in the page (there's access to WebPage object here)


## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019-2020 Jeffrey Bostoen

