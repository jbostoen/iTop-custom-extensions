Just a collection of some general pointers on iTop customization, recommendations ...

# Interesting files

## web/data/datamodel-production.xml 
Compiled data model. Even specifies where a class was created or edited (example: <class id="IOSVersion" _created_in="itop-config-mgmt" _altered_in="itop-config-mgmt"> ). Great resource for dependencies.

## web/log/setup.log
Clues about what went wrong during installations (debugging when developing extensions)
            
# Config of modules / module specific settings
Difference:
* config-itop.php contains the actual settings
* in XML datamodel: module parameters = Contains the module specific DEFAULT parameters

# Access rights and profiles
* the profile you give to an user, is for ALL organizations. No way to differentiate.
* deny > allow


# Presentations

## Using CaseLog
* Don't include it again in your presentation node in the XML. 
* Shown by default at the bottom; but if you include it in presentation, it's shown twice.

## Using linked sets
Version: iTop 2.7.0

* When you're already using columns (col:1, col:2), do not place the lists under the main node. 
* Instead, place these fields in the node of one of the cols. They'll still be transformed automatically.
* Otherwise, an empty column might appear to the left.


# Combining extensions
Since iTop 2.4.0, you can combine extensions as subfolders into one extension. However, make sure these subfolders have different names. Don't simply copy and rename the main folder to create a new extension.

# Troubleshooting

## 'DBObjectSearch::__construct called for an invalid class: ""'
For example, in a Typology Overview. Issue may be seen in datamodel-production.xml:

```
<dashlets>
	...
	<dashlet id="10" xsi:type="DashletBadge">
		<rank>9</rank>
		<class>DocumentType</class>
	</dashlet>
	<dashlet id="901" xsi:type="DashletBadge">
		<rank/>
		<class/>
	</dashlet>
	...
</dashlets>
```

Solution: specify _delta="define" on the dashlet tags


## 'Error: Unknown attribute <attribute name> from class <class name>'
Look for mistakes in...

* translation file
* in AttributeExternalField attributes
* in naming, reconciliation, order

## Naming doesn't work?
In iTop 2.4.1, it seems naming isn't applied to abstract classes such as 'Change'. It must be applied to child classes.

# Module parameters
module_parameters in XML only define the default value of a module parameters, it is not used to set a value for your instance. 
You should only use it when creating your own extension. 

