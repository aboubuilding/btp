<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Canaux de notification par type
    |--------------------------------------------------------------------------
    */
    'canaux' => [
        'database' => env('NOTIFICATIONS_DATABASE', true),
        'mail'     => env('NOTIFICATIONS_MAIL', true),
        'sms'      => env('NOTIFICATIONS_SMS', false), // à venir
    ],

    /*
    |--------------------------------------------------------------------------
    | Canaux par catégorie
    |--------------------------------------------------------------------------
    */
    'categories' => [
        'alertes'         => ['database', 'mail'],
        'informations'    => ['database'],
        'urgences'        => ['database', 'mail'], // + SMS si dispo
    ],

    /*
    |--------------------------------------------------------------------------
    | Seuils de notification (jours avant expiration)
    |--------------------------------------------------------------------------
    */
    'seuils' => [
        'documents'      => 30,
        'contrats'       => 30,
        'habilitations'  => 60,
        'cautions'       => 30,
        'maintenances'   => 15,
        'factures'       => 1,   // Alerte dès le 1er jour de retard
    ],

    /*
    |--------------------------------------------------------------------------
    | Batch / throttling
    |--------------------------------------------------------------------------
    */
    'throttle' => [
        'par_minute' => 60,
        'par_heure'  => 500,
    ],
];