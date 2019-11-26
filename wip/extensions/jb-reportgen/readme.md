# jb-reportgen

Feature: report generator. Quickly add reports to different classes (detail view and list view).

Work in progress. Might need some additional PHP libraries (using composer).

# Requirements

* iTop-dir/web/libext/vendor/autoload.php should be present. Use 'libext' from this repository and run composer update to install all required packages.

Required
- Twig 2.x (iTop still uses Twig 1?)

Recommended
* optional chillerlan\QRCode (using Composer)

# How-to

## Adding reports 
* Add a folder under the templates-folder within this extension with the class name (don't use abstract classes!)
* In this folder, you can create subfolders: *details* and/or *list*
* Create a Twig template (basically a HTML file) and save it in the appropriate folder
* The reports will automatically show in the details view or list view under iTop's *Other actions...*
* The title of the report will be derived from reports.php file

Some vary basic reports are included as an example.

## Making reports available in iTop
Open templates/reports.php.
Typically it looks like this: 'iTop-class-name' => 'view' => 'setting'

Settings are:
* title (how this will be presented within iTop)
* button: boolean (true/false). Show as separate button in toolbar right above details/list view. If false: listed under 'other actions'
* file: name of the template (should be a similar structure in templates: templates/iTop-class-name/which-view/filename.ext)
* optional: parameters
** action: allows for custom actions (create a class implementing iReportGeneratorExtension. Examples: generate XML, PDF, store files, ...)


```
	$aReports = [

		// UserRequest
		'UserRequest' => [
			// Allowed keys: details, list
			'details' => [
				[
					// Allowed keys: 'title' (String), 'button' (true/false), 'file' (string), 'parameters' (hash table; passed in URL)
					// Hash table parameters: 'action' (special parameter)
					'title' => 'Sample report',
					'button' => true,
					'file' => 'basic_details.html'
				],
				[
					'title' => 'Sample report (PDF)',
					'button' => true,
					'file' => 'basic_details.html',
					'parameters' => [
						'action' => 'show_pdf'
					]
				],
				[
					'title' => 'Sample work order',
					'button' => true,
					'file' => 'werkbon.twig'
				]
			],
			'list' => [
				// Other reports 
			]
		]
		
	];
```


## Variables in reports

### Single item (details view)

For details (single object), you can use the variable *item*. It exposes *key* and *fields* (see iTop REST Documentation). Example: item.fields.description 
 
As a bonus: you can also use *item.attachments*. If you use **{% for attachment in item.attachments %} ... {% endfor %}**; you can use the attachment's field properties:
* attachment.fields.contents.mimetype
* attachment.fields.contents.data
* attachment.fields.contents.filename

### Multiple items (list view)
For lists (single or multiple objects), you can use **item** and create things like **{% for item in items % } ... {% endfor %}**

Attachments are also available for each item.


## Using iTop language strings
* If you want to use iTop Language strings, you can! 
There's a Twig Filter named dict_s in templates.
Where in iTop code you would use Dict::S('languagestring'), 
but it's the same as in iTop Portal templates, for example: {{ 'UI:Menu:ReportGenerator:ShowReport'|dict_s }} or {{ 'Class:Ticket/Attribute:ref'|dict_s }}

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


