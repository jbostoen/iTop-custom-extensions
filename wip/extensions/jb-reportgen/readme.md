# jb-reportgen

## What?
Feature: report generator. Quickly add reports to different classes (detail view).

Work in progress.

# Requirements
* Relies on the iTop Connector (found in this repository)
* expects Twig 2.x to be installed in iTop-dir/libext (iTop still uses Twig 1)

## How-to

### Adding reports 
* Under the templates-folder, you should add a folder with the class name (not abstract!). There, you can have a subfolder *details* and/or *list* and put templates in it. 
* Create a Twig template (basically a HTML file) 
* The reports will automatically show in the details view or list view under iTop's *Other actions...*

### Variables in reports

#### Single item (details-view)

For details (single object), you can use the variable *item*. It exposes *key* and *fields* (see iTop REST Documentation). Example: item.fields.description 
 
As a bonus, you can also use *item.attachments*. If you use **{% for attachment in item.attachments %} ... {% endfor %}**; you can use the attachment's properties: **mimetype, data, filename**

For lists (single or multiple objects), you can use **item** and create things like **{% for item in items % } ... {% endfor %}**

### Setting report title
* To determine the title, you can specify the report title between the <title> tags. This is what will be shown in iTop.

### Using iTop language strings
* If you want to use iTop Language strings, you can! There's a Twig Filter named dict_s in templates (where in iTop code you would use Dict::S('languagestring'), but it's the same as in iTop Portal templates, for example: {{ 'UI:Menu:ReportGenerator:ShowReport'|dict_s }}  )


