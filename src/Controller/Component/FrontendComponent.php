<?php

namespace Unimatrix\Frontend\Controller\Component;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\Controller\Component;

/**
 * Admin Component
 * This component loads all other necesary stuff for the frontend,
 * it also handles some custom frontend logic and request filtering
 *
 * @author Flavius
 * @version 0.1
 */
class FrontendComponent extends Component
{
    /**
     * {@inheritDoc}
     * @see \Cake\Controller\Component::initialize()
     */
    public function initialize(array $config) {
        parent::initialize($config);

        // load security
        if(Configure::read('Frontend.security.enabled')) {
            $this->getController()->loadComponent('Security');
            if(Configure::read('Frontend.security.ssl'))
                $this->getController()->Security->requireSecure();
            $this->getController()->loadComponent('Csrf', [
                'httpOnly' => true,
                'secure' => env('HTTPS')
            ]);
        }

        // load required components
        $this->getController()->loadComponent('Unimatrix/Frontend.Captcha');
        $this->getController()->loadComponent('Unimatrix/Frontend.Sitemap');
    }
}
