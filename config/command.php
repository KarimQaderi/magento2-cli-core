<?php

/**
 * Please do not add or remove anything
 */

return [
    'm1' => [
        'title' => 'Upgrade All Code',
        'isPhp' => true,
        'code' => '',
        'deps' => ['m5', 'm3', 'm11', 'm4'],
    ],

    'm11' => [
        'title' => 'Force deploy -f',
        'isPhp' => true,
        'code' => 'rm -rf "{dir}pub/static" && rm -rf "{dir}var/cache" && rm -rf "{dir}var/view_preprocessed"',
        'deps' => ['m2'],
    ],

    'm2' => [
        'title' => 'deploy -f',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" setup:static-content:deploy -f {more}',
        'ask' => [
            'more' => [
                'title' => 'More Append Data Ex: fa_IR',
                'default' => '',
            ],
        ],
    ],

    'm3' => [
        'title' => 'compile',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" setup:di:compile',
    ],

    'm4' => [
        'title' => 'cache:flush',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" cache:flush',
    ],

    'm5' => [
        'title' => 'setup:upgrade',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" setup:upgrade',
    ],

    'm6' => [
        'title' => 'developer',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" deploy:mode:set developer',
    ],

    'm7' => [
        'title' => 'production',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" deploy:mode:set production',
    ],

    'm8' => [
        'title' => 'reindex',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" indexer:reindex',
    ],

    'm9' => [
        'title' => 'resize',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" catalog:image:resize',
    ],

    'm10' => [
        'title' => 'Create Admin',
        'isPhp' => true,
        'code' => '{appendToFirstCode} {php} "{dir}bin/magento" admin:user:create --admin-user="{username}" --admin-password="{password}" --admin-email="{email}" --admin-firstname="{firstname}" --admin-lastname="{lastname}"',
        'ask' => [
            'username' => [
                'title' => 'User Name',
                'default' => 'Magento',
            ],
            'password' => [
                'title' => 'Password',
                'default' => '',
            ],
            'email' => [
                'title' => 'Email',
                'default' => 'magento@magento.magento',
            ],
            'firstname' => [
                'title' => 'Firstname',
                'default' => 'Magento',
            ],
            'lastname' => [
                'title' => 'Lastname',
                'default' => 'Magento',
            ],
        ],
    ],
];
