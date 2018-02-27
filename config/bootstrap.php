<?php

use Cake\Core\Plugin;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Http\ServerRequestFactory;
use Unimatrix\Cake\Error\Middleware\EmailErrorHandlerMiddleware;
use Unimatrix\Frontend\Http\Middleware\CsrfProtectionMiddleware;

// load Unimatrix Cake
Plugin::load('Unimatrix/Cake');

// get url path
$url = explode('/', ServerRequestFactory::fromGlobals()->url);

// is cli or backend or debug_kit? don't continue
if(PHP_SAPI === 'cli'
    || (Configure::read('Backend') && $url[0] === 'backend')
    || $url[0] === 'debug_kit' || $url[0] === 'debug-kit')
        return;

// attach middleware
EventManager::instance()->on('Server.buildMiddleware', function ($event, $queue) {
    // EmailErrorHandlerMiddleware
    $queue->insertBefore('Cake\Routing\Middleware\AssetMiddleware',
        EmailErrorHandlerMiddleware::class);

    // CsrfProtectionMiddleware
    if(Configure::read('Frontend.security.enabled'))
        $queue->add(CsrfProtectionMiddleware::class);
});
