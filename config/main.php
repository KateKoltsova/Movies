<?php

return [
    'app_name' => 'Test framework',
    'components' => [
        'router' => [
            'factory' => Framework\Components\RouterFactory::class,
            'arguments' => [
                'routes' => __DIR__ . '/../routes/routes.php'
            ]
        ],

    ]
];
