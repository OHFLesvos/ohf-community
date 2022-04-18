<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Diese Kombination aus Zugangsdaten wurden nicht in unserer Datenbank gefunden.',
    'password' => 'Das angegebene Passwort ist falsch.',
    'throttle' => 'Zu viele Loginversuche. Versuchen Sie es bitte in :seconds Sekunden nochmal.',

    'permissions' => [
        'view_usermgmt' => 'Benutzerverwaltung: Benutzer und Rollen betrachten',
        'usermgmt_manage_users' => 'Benutzerverwaltung: Benutzer erstellen, bearbeiten und löschen',
        'usermgmt_manage_roles' => 'Benutzerverwaltung: Rollen erstellen, bearbeiten und löschen',

        'configure_common_settings' => 'Allgemeine Einstellungen konfigurieren',

        'create_badges' => 'Badges: Badges erstellen',

        'view_fundraising_donors_donations' => 'Spendenverwaltung: Spender & Spenden betrachten',
        'manage_fundraising_donors_donations' => 'Spendenverwaltung: Spender & Spenden verwalten',
        'view_fundraising_reports' => 'Spendenverwaltung: Berichte betrachten',
        'accept_fundraising_donations_webhooks' => 'Spendenverwaltung: Webhooks akzeptieren',

        'view_transactions' => 'Buchhaltung: Transaktionen betrachten',
        'create_transactions' => 'Buchhaltung: Transaktionen erfassen',
        'update_transactions' => 'Buchhaltung: Transaktionen bearbeiten',
        'update_transaction_metadata' => 'Buchhaltung: Metadaten von Transaktionen bearbeiten',
        'delete_transactions' => 'Buchhaltung: Transaktionen löschen',
        'book_externally' => 'Buchhaltung: Transaktionen extern verbuchen',
        'view_summary' => 'Buchhaltung: Zusammenfassung ansehen',
        'manage_suppliers' => 'Buchhaltung: Lieferanten verwalten',
        'view_budgets' => 'Buchhaltung: Budgets ansehen',
        'manage_budgets' => 'Buchhaltung: Budgets verwalten',
        'configure_accounting'  => 'Buchhaltung: Einstellungen bearbeiten',

        'view_community_volunteers' => 'Community Volunteers: Community Volunteers betrachten',
        'manage_community_volunteers' => 'Community Volunteers: Community Volunteers verwalten',

        'register_visitors' => 'Besucher: Registrieren',
        'export_visitors' => 'Besucher: Exportieren',
    ],
];
