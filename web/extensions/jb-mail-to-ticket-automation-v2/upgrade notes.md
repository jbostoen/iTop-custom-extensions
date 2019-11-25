# Upgrade notes
Backward compatibility is something that is important.
However, some upgrades might break things because technology or use cases change.

## Upgrading from before 2.6.191123?

**Automatically taken care of**
* Some enum values have changed. Generic 'fallback' values have been renamed to more specific ones, even if it's the only fallback option.

**Manual check required:**
* placeholders
  * the 'mail->*' placeholders have changed and now end with $ (more in line with iTop's other placeholders)
* remove/ignore title patterns (where email is processed, so do not confuse it with undesired title patterns)
  * split into two separate policies for more flexibility 

**Settings (iTop Configuration) that are deprecated (but working):**
This extension was forked from Combodo's Mail to Ticket Automation. 
Some features are implemented in a different way. 
Hence some settings no longer make sense and are being deprecated.
For now, they still work.

* exclude_attachment_types
