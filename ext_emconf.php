<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Altcha Captcha for Powermail',
    'description' => 'Accessible and GDPR compliant proof-of-work (pow) captcha for powermail, no external service required',
    'version' => '10.0.0',
    'category' => 'service',
    'author' => '599media Dev-Team',
    'author_email' => 'info@599media.de',
    'author_company' => '599media GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-13.4.99',
            'php' => '7.4.0-8.4.99',
            'powermail' => '8.5.0-13.99.99'
        ]
    ],
    'state' => 'stable',
    'autoload' => [
        'classmap' => [
            'Classes'
        ],
        'psr-4' => [
            'Fnn\\FnnPowermailAltcha\\' => 'Classes',
            'AltchaOrg\\Altcha\\' => 'Vendor/altcha-lib-php/src'
        ]
    ]
];
