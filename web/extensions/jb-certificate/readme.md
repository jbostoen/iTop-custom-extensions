# jb-certificate-main

## What?
Adds Certificate class and some link classes. Keep track of all sorts of certificates generated for FunctionalCIs (Server, VirtualMachine) and WebServers.

It also integrates with the Software Management extension so a certificate can be linked to a software installation.

## Cookbook

* add new Class Certificate, with relations (impacts/depends on)
* add tabs to existing classes to show relations: link Certificate to FunctionalCI (Server, VirtualMachine, WebServer)
* adds additional changes to datamodel based upon installation of another extension
* using the PrefillCreationForm() method

## Upgrade notes

### From earlier versions to 15th of June, 2020
* Certificate is now a subclass of FunctionalCI. Now you can link it to a Ticket
* lnkCertificateToWebServer has been removed, in favor of the generic lnkCertificateToFunctionalCI
* Added basic integration with IzSoftwareInstallation (found in jb-software-mgmt)
* renamed fields
  * provider_org_id -> issuer_org_id
  * date_creation -> date_not_valid_before
  * date_expiration -> date_not_valid_after

## License
https://www.gnu.org/licenses/gpl-3.0.en.html
Copyright (C) 2019-2020 Jeffrey Bostoen


