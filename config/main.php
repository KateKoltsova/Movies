<?php

return [
    'app_name' => 'Movies',
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
                'path' => dirname(__DIR__, 1) . '/public/files/images/'
            ]
        ]

    ],
    'resources' => [
        'views' => dirname(__DIR__, 1) . '/resources/views/'
    ]
];
