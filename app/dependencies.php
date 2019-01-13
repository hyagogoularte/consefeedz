<?php

require_once __DIR__ . '/config.php';
$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// Database settings
$container['db'] = function ($c) {
    $db = $c['settings']['db'];

    $dsn = "mysql:host=" . $db['host'] . ":" . $db['port'] . ";dbname=" . $db['dbname'];
    $username = $db['user'];
    $password = $db['pass'];

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// Sessions
$container['session'] = function ($c) {
    return new \SlimSession\Helper;
};

// Auth
$container['auth'] = function ($c) {
    return new App\Library\AuthLibrary($c->get('db'), $c->get('logger'));
};

// User
$container['user'] = function ($c) {
    return new App\Library\UserLibrary($c->get('db'), $c->get('auth'), $c->get('logger'));
};

// User
$container['car'] = function ($c) {
    return new App\Library\CarLibrary($c->get('db'), $c->get('auth'), $c->get('logger'));
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction($c->get('view'), $c->get('logger'), $c->get('car'));
};

$container[App\Action\CarAction::class] = function ($c) {
    return new App\Action\CarAction($c->get('view'), $c->get('logger'), $c->get('car'));
};

$container[App\Action\LoginAction::class] = function ($c) {
    return new App\Action\LoginAction($c->get('view'), $c->get('logger'), $c->get('auth'), $c->get('session'));
};

$container[App\Action\RegisterAction::class] = function ($c) {
    return new App\Action\RegisterAction($c->get('view'), $c->get('logger'), $c->get('session'), $c->get('auth'), $c->get('user'));
};

$container[App\Action\LogoutAction::class] = function ($c) {
    return new App\Action\LogoutAction($c->get('session'));
};

// -----------------------------------------------------------------------------
// Middleware factories
// -----------------------------------------------------------------------------
$container[App\Middleware\AuthMiddleware::class] = function ($c) {
    return new App\Middleware\AuthMiddleware($c->get('auth'), $c->get('logger'), $c->get('session'));
};
