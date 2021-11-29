<?php

return [
    'type' => 'ldap', //Choose the connection type 'ldap' or 'oauth'
    'database' => [
        'column' => '' // specify the column to compare in the table user
    ],
    'ldap' => [
    'LDAP_HOST' => '', //Enter the LDAP HOST
    'LDAP_PORT' => '' //Enter the LDAP PORT
    ],
    'oauth' => [
        'clientId'                => '',    //The client ID assigned to you by the provider
        'resource'                => '', //The base url login adfs
        'redirectUri'             => '', //The url acces ADFS login
        'urlAuthorize'            => '', //The url oauth authorize
        'urlAccessToken'          => '', //The endpoint Token
        'urlResourceOwnerDetails' => '' //The endpoint Token
    ],
    'route' => [
        'acces' => 'home' //Specify the redirection route
    ]
];
?>