<?php

use Cake\Core\Plugin;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Unimatrix\Cake\Error\Middleware\EmailErrorHandlerMiddleware;

// load Unimatrix Cake
Plugin::load('Unimatrix/Cake');

// is cli or backend or debug_kit? don't continue
if(PHP_SAPI === 'cli' || Configure::read('Backend') && explode('/', env('REQUEST_URI'))[1] === 'backend'
    || explode('/', env('REQUEST_URI'))[1] === 'debug_kit')
        return;

// attach middleware
EventManager::instance()->on('Server.buildMiddleware', function ($event, $queue) {
    // EmailErrorHandlerMiddleware
    $queue->insertAt(0, EmailErrorHandlerMiddleware::class);

    // CsrfProtectionMiddleware
    if(Configure::read('Frontend.security.enabled'))
        $queue->add(new CsrfProtectionMiddleware([
            'httpOnly' => true,
            'secure' => env('HTTPS'),
            'cookieName' => 'frontend_csrf_token',
        ]));
});
