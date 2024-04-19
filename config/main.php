<?php

use Aigletter\App\Components\Test\TestFactory;

return [
    'app_name' => 'Test framework',
    'components' => [
        'router' => [
            'factory' => Aigletter\Framework\Components\RouterFactory::class,
            'arguments' => [
                'routes' => __DIR__ . '/../routes/routes.php'
            ]
        ],
        'test' => [
            'factory' => TestFactory::class,
        ]
    ]
];
