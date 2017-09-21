<?php

use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Http\Middleware\CsrfProtectionMiddleware;

// is backend or debug_kit? don't continue
if(Configure::read('Backend') && explode('/', env('REQUEST_URI'))[1] === 'backend'
    || explode('/', env('REQUEST_URI'))[1] === 'debug_kit')
        return;

// attach middleware
EventManager::instance()->on('Server.buildMiddleware', function ($event, $queue) {
    // CsrfProtectionMiddleware
    if(Configure::read('Frontend.security.enabled'))
        $queue->add(new CsrfProtectionMiddleware([
            'httpOnly' => true,
            'secure' => env('HTTPS'),
            'cookieName' => 'frontend_csrf_token',
        ]));
});
