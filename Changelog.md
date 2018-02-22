# Changelog

## 1.9.2

* Upgraded icon set to Font Awesome 5

## 1.9.1

* Date of birth selection in the bank is now using simple text field instead of a date selector.

## 1.9.0

* Added scheduler/calendar module
* Added list of popular names to person report
* Updated application framework to Laravel 5.6
* Updated CSS framework to Bootstrap 4.0

## 1.8.20

* Added functionality to remove duplicated persons

## 1.8.19

* Added "Date of birth" and "Registered" field to "People" page.
* Added column sorting in "People" page.
* Added possibilty to assign relations to person (father, mother, child, partner)
* Replaced text-field autocomplete library
* Use pie charts to show person demographics and gender in person report

## 1.8.18

* Small improvements in reports
* Updated laravel framework and related libraries to latest patch release
* Added pagination to table of transactions in persons detail view, show author and date/time in human-friendly format
* Improved calculation of frequent visitor marker
* In the bank, transactions as well as boutique and diapers coupons can be undone (if they are not older than 5 minutes)
* Added a quick date of birth selector in the banks person search result screen
* Added sceen to configure code card document

## 1.8.17

* Added view to read the changelog. Changelog card has been added to the dashboard.
* Persons will now be marked as "frequent visitor" based on their number of visits at the bank. Threshold can be defined in bank settings.
* People report has been enhanced, showing important numbers at the top of the page.
