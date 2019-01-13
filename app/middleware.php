<?php

$app->add(new \Slim\Middleware\Session([
    'name' => 'Consefeedz',
    'autorefresh' => true,
    'lifetime' => '1 hour',
]));
