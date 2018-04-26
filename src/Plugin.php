<?php

namespace Unimatrix\Frontend;

use Cake\Core\Configure;
use Cake\Core\BasePlugin;
use Cake\Http\ServerRequestFactory;
use Unimatrix\Frontend\Http\Middleware\CsrfProtectionMiddleware;

/**
 * Frontend Plugin
 *
 * @author Flavius
 * @version 1.0
 */
class Plugin extends BasePlugin
{
    /**
     * Plugin name
     * @var string
     */
    protected $name = 'Unimatrix/Frontend';

    /**
     * Should this plugin be activated
     * @return bool
     */
    protected function inEffect() {
        $url = explode('/', ServerRequestFactory::fromGlobals()->getPath());

        // is backend? abort
        if(Configure::read('Backend') && $url[1] === 'backend')
            return false;

        // is debug kit? abort
        if($url[1] === 'debug_kit' || $url[1] === 'debug-kit')
            return false;

        // valid
        return true;
    }

    /**
     * {@inheritDoc}
     * @see \Cake\Core\BasePlugin::middleware()
     */
    public function middleware($middleware) {
        if($this->inEffect()) {
            // CsrfProtectionMiddleware
            if(Configure::read('Frontend.security.enabled'))
                $middleware->add(CsrfProtectionMiddleware::class);
        }

        return $middleware;
    }
}
