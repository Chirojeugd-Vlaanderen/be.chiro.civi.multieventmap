# be.chiro.civi.multieventmap

this civicrm extension creates a map of multiple event locations

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.4+
* CiviCRM

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl be.chiro.civi.multieventmap@https://github.com/Chirojeugd-Vlaanderen/be.chiro.civi.multieventmap/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/Chirojeugd-Vlaanderen/be.chiro.civi.multieventmap.git
cv en multieventmap
```

## Usage

To see events on the map make shure geocoding is enabled (administer -> system settings -> mapping and geocoding. Once geocoding is enabled every time an address is saved the address should get a geocoding. 

The extension adds a menu item called event map in the events category. Once this is clicked a map of all future events is shown. The header events without locations shows all future events without geocoding. for the address. The filter on top of the map can be used to determine which events are drawn on the map.

## Known Issues

(* FIXME *)
