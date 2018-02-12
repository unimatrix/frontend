<?php

use Cake\Core\Plugin;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Unimatrix\Cake\Error\Middleware\EmailErrorHandlerMiddleware;
use Unimatrix\Frontend\Http\Middleware\CsrfProtectionMiddleware;

// load Unimatrix Cake
Plugin::load('Unimatrix/Cake');

// get url path
$url = explode('/', env('REQUEST_URI'));

// is cli or backend or debug_kit? don't continue
if(PHP_SAPI === 'cli'
    || (Configure::read('Backend') && $url[1] === 'backend')
    || $url[1] === 'debug_kit' || $url[1] === 'debug-kit')
        return;

// attach middleware
EventManager::instance()->on('Server.buildMiddleware', function ($event, $queue) {
    // EmailErrorHandlerMiddleware
    $queue->insertAt(0, EmailErrorHandlerMiddleware::class);

    // CsrfProtectionMiddleware
    if(Configure::read('Frontend.security.enabled'))
        $queue->add(CsrfProtectionMiddleware::class);
});
