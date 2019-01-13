<?php

$app->get('/', App\Action\HomeAction::class)->add(App\Middleware\AuthMiddleware::class);

$app->get('/auth/[{option}]', App\Action\LoginAction::class);
$app->post('/login', App\Action\LoginAction::class . ':doLogin');
$app->post('/register', App\Action\RegisterAction::class . ':register');

$app->get('/logout', App\Action\LogoutAction::class);

$app->get('/car[/{id}]', App\Action\CarAction::class)->add(App\Middleware\AuthMiddleware::class);
$app->post('/car/new', App\Action\CarAction::class . ':createCar')->add(App\Middleware\AuthMiddleware::class);
$app->post('/car/edit', App\Action\CarAction::class . ':editCar')->add(App\Middleware\AuthMiddleware::class);
$app->post('/car/delete', App\Action\CarAction::class . ':deleteCar')->add(App\Middleware\AuthMiddleware::class);
