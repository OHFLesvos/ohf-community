# Changelog

## 1.13.2

* Fixed issue with last coupon handout date in export

## 1.13.1

* Some UI fixes related to coupons

## 1.13.0

* New generalized coupon handout system
* Added dedicated permission for exporting list of persons.

## 1.12.6

* Donors widget shows number of donations.
* Added privacy statement to login/registration screen.
* A mail notification is sent to admins when new user registers.

## 1.12.5

* UI fixes

## 1.12.4

* Show administrators in pricacy report
* Logviewer table improvements
* Added view about latest donations

## 1.12.3

* Adder user privilege repots to reporting screen
* Show human readable date in log viewer
* Translated report names
* Reordered dashboard widgets
* Translated permission names
* Add report of users with access to sensitive data

## 1.12.2

* Added option to disable 2FA in user management.
* Added audit logging for user profile actions.
* Added basic log viewer.

## 1.12.1

* Fixed 2FA code generation.

## 1.12.0

* Improved mobile UX for users and roles views.
* Implemented Tow-Factor Authentication (2FA).
* Boutique coupons are now only available if age of 15 or above (or no age defined).

## 1.11.4

* Fixed gender selection when registering children.
* Added bottom navigation to bank views, removed bank index view.

## 1.11.3

* Updated Laravel to v5.6.9.
* Added checkbox for remembering the session to login view, default is false.
* Optimized login view for small screens.
* UI: Implemented custom checkbox/radio style.
* Added possibility to edit and delete donations.

## 1.11.2

* Improved "date of birth" validation in persons/bank screens.
* Don't show donors donations in donor overview screen.
* Added possibility to register incoming/outgoing articles for any day in the past.

## 1.11.1

* Added "remarks" field to donors.

## 1.11.0

* When setting / changing a user password, it will now be validated against the ["Have I been pwned"](https://haveibeenpwned.com/) service.
* Laravel updated to 5.6.7.
* Added "snackbar"-style notifications.
* Added "purpose" and "reference" fields to donations.
* Added report about user permissions.

## 1.10.5

* Export of donors donations now contain the sum, and are grouped by year. Export is only possible if there are donatinons.

## 1.10.4

* Added ability to export donations of specifc donor into Excel file.
* Correctly formatted "currency" field in Excel export.

## 1.10.3

* Exchange rate is queried from EZV based on the selected day.
* Fixed syntax in .env.example.

## 1.10.2

* When register a donation in a foreign currency, the EZV Database is automatically queried for the exchange rate.
* Added USD to the list of supported currencies for donations.

## 1.10.1

* Added worker checkbox for person.

## 1.10.0

* Language support (english/german) added.
* Donations management added.

## 1.9.1

* Date of birth selection in the bank is now using simple text field instead of a date selector.

## 1.9.0

* Added scheduler/calendar module.
* Added list of popular names to person report.
* Updated application framework to Laravel 5.6.
* Updated CSS framework to Bootstrap 4.0.

## 1.8.20

* Added functionality to remove duplicated persons.

## 1.8.19

* Added "Date of birth" and "Registered" field to "People" page.
* Added column sorting in "People" page.
* Added possibilty to assign relations to person (father, mother, child, partner).
* Replaced text-field autocomplete library.
* Use pie charts to show person demographics and gender in person report.

## 1.8.18

* Small improvements in reports.
* Updated laravel framework and related libraries to latest patch release.
* Added pagination to table of transactions in persons detail view, show author and date/time in human-friendly format.
* Improved calculation of frequent visitor marker.
* In the bank, transactions as well as boutique and diapers coupons can be undone (if they are not older than 5 minutes).
* Added a quick date of birth selector in the banks person search result screen.
* Added sceen to configure code card document.

## 1.8.17

* Added view to read the changelog. Changelog card has been added to the dashboard.
* Persons will now be marked as "frequent visitor" based on their number of visits at the bank. Threshold can be defined in bank settings.
* People report has been enhanced, showing important numbers at the top of the page.
