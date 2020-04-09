# Cascading external fields

You can have something like Person -> Ticket, and use AttributeExternalField to show the person's phone number in a Ticket. 
This also works for pictures.

You can have something like Person -> Ticket -> Intervention, and Intervention can use an AttributeExternalField if you inherit it in Pass. 
This could be done as a field which you hide from your presentation. This way, you could show for example the Person's phone number on the Intervention.

However, if you show the Person's picture on an Intervention; you will get errors when creating a Intervention, even if you don't use the Picture field in the Intervention. This is a known issue which might get fixed in the future.

# Commenting

Avoid commenting above the ```<itop_design>``` tag.
It might cause iTop to ignore everything.
( tested with iTop 2.6 )

# Naming

## Friendyname
You can actually use the "friendlyname" attribute of a class you refer to (ExternalKey) in the "naming" attribute of the class where you have this ExternalKey field.
