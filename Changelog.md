# Changelog

## 2.3.3

* Publicly viewable tags if articles are public

## 2.3.2

* Fix public view permission for wiki articles if logged in

## 2.3.1

* Library update

## 2.3.0

* Logistics module added with suppliers

## 2.2.2

* Added share button to publicly available articles

## 2.2.1

* Fix error while creating article without public flag

## 2.2.0

* PDF export of articles with support for images and internal links
* Added styles to editor
* Laravel 5.7 update
* Articles can be made public

## 2.1.1

* PDF export of KB articles

## 2.1.0

* KB articles navigation improved
* WYSIWYG editor in KB articles
* Small bugfixes due do modularization
* Library updates

## 2.0.2

* Changed gravatar fallback to identicon

## 2.0.1

* Fix issue in coupon handout.

## 2.0.0

* Modularization of functionality.
* Gravatar user profile image added.
* Update icon library (Font awesome 5).
* Added user profile at top right title bar.
* Small layout fixes.
* Welcome message on dashboard only after login.

## 1.24.0

* Updated spreadsheet export/import library

## 1.23.9

Badges:

* New list input

## 1.23.8

Donors:

* More powerful filter in donor search, can search for address, phone, mail

## 1.23.7

Helpers:

* Report added

## 1.23.6

Accounting:

* Added previous and next transaction buttons

User management:

* Larger amount of results per page in user and role pagination

Library:

* Fixed book lending check in bank

Donor management:

* Stripe file import
* Tag support for donors

## 1.23.5

Accounting:

* Simplified receipt picture upload if its missing

## 1.23.4

Library:

* Fixed permission issue

## 1.23.3

Library:

* Fixed ISBN validation
* Book creation and editing capabilities
* Register persons in library

## 1.23.2

Library:

* Book lending limits per person.
* Added possibility to edit book.

## 1.23.1

* Library improvements

## 1.23.0

* Added Library module

## 1.22.1

* Library update
* Fix badge issue with reissuing ID, badges with same name
* Added column sets and orientation to helper export

## 1.22.0

People:

* Removed barely used temp and medical number fields from person records
* Case-number is now stored in hashed form, and no longer displayed to the user

Helpers:

* Case work permissions
* Autocomplete lanugage, responsibilities
* Fix vcard number export
* DB encryption for sensitive helper data

## 1.21.5

* Added staff card number

## 1.21.4

Helpers:

* Helper future leaving message clarified
* Gender grouping
* Null values in certain groupings
* WhatsApp link adds persons name
* Export formats and scope
* Helper vCard
* Helper grid view
* Added option to download portraits
* Enforce 2:3 aspect ratio for portraits
* Added company and photo to helper vCard

Shop:

* Added barber shop list / check-in
* Shop coupon validity can now be configured

Badge:

* Added badge creator screen

## 1.21.3

Helpers:

* Fixed permission check

## 1.21.2

People:

* Added bulk delete / merge
* Family name shown uppercase
* Coupons can be allowed for helpers or not

Helpers:

* Regular scope
* Widget

## 1.21.1

Helpers:

* Added helpers module

## 1.20.11

Accounting:

* Default order transactions by "registered"
* Alternative receipt image upload and editor

## 1.20.10

General:

* Replacement of QR code scanner library

Accounting:

* Export with summary
* Remarks and wallet owner column added

Shop:

* Added authorization

## 1.20.9

General:

* Libraries updated

Shop:

* Minor fixes

## 1.20.8

Shop:

* Minor fixes

## 1.20.7

Shop:

* Accept coupons from today or yesterday

## 1.20.6

Shop:

* Improved shop interface

## 1.20.4

Shop:

* Improved shop interface

## 1.20.3

Accounting:

* Exact filter for project

## 1.20.0

Bank:

* Added daily spending limit option to coupons
* Removed card-registration without person, added card when registering person

## 1.19.14

Accounting:

* Show correct transaction author

Bank:

* Fixed date parsing

## 1.19.13

Accounting:

* Show difference in summary
* Jump to transaction details if receipt no is filtered

## 1.19.12

Accounting:

* No autocompletition for description

## 1.19.11

Accounting:

* Color description filter column
* Sort excel export by date ascending
* Added filter for having no receipt

## 1.19.10

Accounting:

* Filter for description fix

## 1.19.9

Accounting:

* Filter for description
* Filter autocomplete

## 1.19.8

Accounting:

* Fix filter
* Show total summary when filtered in mobile view
* Added save and continue button

## 1.19.7

Accounting:

* Added "today" filter
* Project and beneficiary filter now as wildcard

## 1.19.6

Accounting:

* Fix filter

## 1.19.5

Accounting:

* Auto-jump do amount after choosing date
* Paginate by 100 records instead of 25
* Show receipt number in mobile view

Donations:

* Fix table cell in mobile view

## 1.19.4

Accounting:

* Filter for transactions added

## 1.19.3

Fundraising:

* Logging in RaiseNow Webhook

## 1.19.2

Fundraising:

* Support for RaiseNow Webhook

## 1.19.1

Inventory:

* Fix widget when empty storages

## 1.19.0

Accounting:

* Fixed month selection in summary when no transactions in current month have been made

Inventory:

* New module added for inventory management

## 1.18.0

General:

* Widget restyling
* Added lity lightbox

Accounting:

* Added accounting sum up when filtered
* Added wallet calculation with previous months
* Auto-suggest description

## 1.17.7

Accounting:

* Added rilldown

## 1.17.6

Accounting:

* Added excel export

## 1.17.5

Accounting_

* Added image downscaling when uploading receipt image

## 1.17.4

Accounting:

* Minor fixes

## 1.17.3

Accounting:

* Unique form autocomplete

## 1.17.2

Accounting:

* Fixed date edit issue

## 1.17.1

Accounting:

* Fixed issue with encryption (field size)

## 1.17.0

Accounting:

* Added basic money transaction registration for accounting

## 1.16.5

* Small fixed in wiki module

## 1.16.4

* Added wiki links to wiki using [[slug]] syntax.

## 1.16.3

* Added lates changes view to wiki
* Added google maps API key and keyword to wiki

## 1.16.2

* Fix wiki article tag creation

## 1.16.1

* Fixed wiki article formatting

## 1.16.0

* Added wiki article module with tag support

## 1.15.2

* More prominent donor remarks

## 1.15.1

* Added "in name of" column to donors

## 1.15.0

* Added "salutation" field to donors
* Added "thanked" field to donations

## 1.14.2

* Fixed issues with revoking cards

## 1.14.0

* New scanned cards screen added
* Remove buttons not maching age-restrictions when updating person age
* Added quick-nationality select to persons in bank

## 1.13.12

* Fixed issue with creating new person and remembering name

## 1.13.11

* Updated libraries
* Card scanner encrypts QR code with sha256

## 1.13.10

* UI improvements in user and role manager
* Updated libraries
* Added correspondence language to donors

## 1.13.9

* Bank search shows family relations

## 1.13.8

* Fixed coupon return transactions using audit model
* Removed unnecessary user reference in coupon handouts and returns (since we now have audits)
* Improved file upload control
* Added search for family members in bank

## 1.13.7

* Added auditing for coupon handouts and returns
* Improved coupon managmenet UI

## 1.13.6

* Added translations for bank actions

## 1.13.5

* Coupon deposit show latest changed time and author

## 1.13.4

* Added coupon manager

## 1.13.3

* Coupons: Add "returnable" property
* Coupons: Add "enabled" property

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
