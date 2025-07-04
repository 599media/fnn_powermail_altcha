<?php
declare(strict_types=1);

defined('TYPO3_MODE') || defined('TYPO3') || die('Access denied.');

$ll = 'LLL:EXT:fnn_powermail_altcha/Resources/Private/Language/locallang_db.xlf:tx_fnnpowermailaltcha_domain_model_challenge.';

return [
    'ctrl' => [
        'title' => $ll . 'challenge',
        'label' => 'challenge',
        'label_alt' => 'uid,challenge',
        'label_alt_force' => 0,
        'iconfile' => 'EXT:fnn_powermail_altcha/Resources/Public/Icons/tx_fnnpowermailaltcha_domain_model_challenge.svg',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'searchFields' => 'uid,challenge',
    ],
    'types' => [
        0 => [
            'showitem' => '
                challenge,expires,solved,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,hidden'
        ]
    ],
    'columns' => [
        'pid' => [
            'label' => 'pid',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'crdate' => [
            'label' => 'crdate',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tstamp' => [
            'label' => 'tstamp',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'challenge' => [
            'label' => $ll . 'challenge',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 1024,
                'required' => true,
            ],
        ],
        'expires' => [
            'label' => $ll . 'expires',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 10,
                'required' => true,
            ]
        ],
        'solved' => [
            'label' => $ll . 'isSolved',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ]
    ]
];
