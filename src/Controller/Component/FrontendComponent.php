<?php

namespace Unimatrix\Frontend\Controller\Component;

use Cake\I18n\I18n;
use Cake\Core\Configure;
use Cake\Controller\Component;

/**
 * Frontend Component
 * This component loads all other necesary stuff for the frontend,
 * it also handles some custom frontend logic and request filtering
 *
 * @author Flavius
 * @version 1.1
 */
class FrontendComponent extends Component
{
    /**
     * {@inheritDoc}
     * @see \Cake\Controller\Component::initialize()
     */
    public function initialize(array $config) {
        parent::initialize($config);

        // we need these
        $controller = $this->getController();
        $session = $controller->request->getSession();

        // load security
        if(Configure::read('Frontend.security.enabled')) {
            $controller->loadComponent('Security');
            if(Configure::read('Frontend.security.ssl'))
                $controller->Security->requireSecure();
        }

        // set locale based on session
        if($session->check('App.locale'))
            I18n::setLocale($session->read('App.locale'));

        // load required components
        $controller->loadComponent('Unimatrix/Frontend.Captcha');
        $controller->loadComponent('Unimatrix/Frontend.Sitemap');
    }
}
