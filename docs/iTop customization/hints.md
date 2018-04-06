Just a collection of some general pointers on iTop customization, recommendations ...


# Interesting files
##web/data/datamodel-production.xml 
Compiled data model. Even specifies where a class was created or edited (example: <class id="IOSVersion" _created_in="itop-config-mgmt" _altered_in="itop-config-mgmt"> ). Great resource for dependencies.
            
# Config of modules / module specific settings
Difference:
* config-itop.php contain the actual settings
* in XML datamodel: module parameters = Contains the module specific DEFAULT parameters

# Access rights and profiles
* the profile you give to an user, is for ALL organizations. No way to differentiate.
* deny > allow

# Troubleshooting

## 'DBObjectSearch::__construct called for an invalid class: ""'
For example, in a Typology Overview. Issue may be seen in datamodel-production.xml:

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

Solution: specify _delta="define" on the dashlet tags


## 'Error: Unknown attribute energysource from class Vehicle'
Look for a mistake in a translation file


  
  
