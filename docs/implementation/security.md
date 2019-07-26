
= Prevent configuration being edited from within iTop =
There's a few risks related to allowing interactive editing of the configuration file.
Anyone getting iTop admin rights, could easily abuse this.

Therefore, it's recommended to lock this:

$MyModuleSettings = array(

       'itop-config' => Array(
                'config_editor' => 'disabled'
        ),
		
		...
		


