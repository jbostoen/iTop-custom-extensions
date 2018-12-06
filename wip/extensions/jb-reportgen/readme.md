# jb-reportgen

Feature: report generator. Quickly add reports to different classes (detail view).

Work in progress.

# Requirements
* Relies on the iTop Connector (found in this repository)
* expects Twig 2.x to be installed in iTop-dir/libext (iTop still uses Twig 1)

# Use
* under the templates-folder, you should add a folder with the class name (not abstract!) and put templates in it. 
* in a template file (which is a Twig/HTML file), you can specify the report title between the <title> tags.
* bonus: you can use S('languagestring') in templates (where in iTop code you would use Dict::S('languagestring') )




