# jb-reportgen

Feature: report generator. Quickly add reports to different classes (detail view and list view).

Work in progress. 

# Requirements

* iTop-dir/web/libext/vendor/autoload.php should be present. Use 'libext' from this repository and run composer update to install all required packages.

Required
- iTop_REST class (found in this repository)
- Twig 2.x (iTop still uses Twig 1?)

Recommended
- optional chillerlan\QRCode

# How-to

## Adding reports 
* Under the templates-folder, you should add a folder with the class name (not abstract!). There, you can have a subfolder *details* and/or *list* and put templates in it. 
* Create a Twig template (basically a HTML file) 
* The reports will automatically show in the details view or list view under iTop's *Other actions...*

Some vary basic reports are included as an example.

## Variables in reports

### Single item (details view)

For details (single object), you can use the variable *item*. It exposes *key* and *fields* (see iTop REST Documentation). Example: item.fields.description 
 
As a bonus, you can also use *item.attachments*. If you use **{% for attachment in item.attachments %} ... {% endfor %}**; you can use the attachment's properties: **mimetype, data, filename**

### Multiple items (list view)
For lists (single or multiple objects), you can use **item** and create things like **{% for item in items % } ... {% endfor %}**

## Setting report title
* To determine the title, you can specify the report title between the <title> tags. This is what will be shown in iTop.

## Using iTop language strings
* If you want to use iTop Language strings, you can! 
There's a Twig Filter named dict_s in templates.
Where in iTop code you would use Dict::S('languagestring'), 
but it's the same as in iTop Portal templates, for example: {{ 'UI:Menu:ReportGenerator:ShowReport'|dict_s }}

Hint: you can also use this for the title of the report.

## Using QR codes
A Twig filter is available to convert text/URLs to QR-code. {{ 'this string will be converted'|qr }}

## Cookbook

PHP
- shows how to add an item to iTop's "Other actions" menu in both list view and detail view
- shows how to obtain iTop from data and render it using a Twig template of your own
- shows how to add custom filters to Twig

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019 Jeffrey Bostoen


