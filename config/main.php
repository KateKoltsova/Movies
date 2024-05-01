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
        'imageService' => [
            'class' => App\Services\ImageService::class,
            'arguments' => [
                'path' => dirname(__DIR__, 1) . '/files/images/'
            ]
        ]

    ]
];
