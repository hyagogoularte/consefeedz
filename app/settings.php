<?php

require_once __DIR__ . '/config.php';

return [
    'settings' => [
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => DISPLAY_ERRORS,
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log',
        ],
        'db' => [
            'host' => DB_HOST,
            'port' => DB_PORT,
            'user' => DB_USER,
            'pass' => DB_PASS,
            'dbname' => DB_NAME,
        ],
    ],
];
