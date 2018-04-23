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
 * To use switch between languages use the following in your controller:
 * ---------------------------------------------------------------------
 *     public function ro() {
 *         $this->request->getSession()->delete('App.locale');
 *         $this->redirect($this->request->referer());
 *     }
 *     public function en() {
 *         $this->request->getSession()->write('App.locale', 'en_US');
 *         $this->redirect($this->request->referer());
 *     }
 * ---------------------------------------------------------------------
 *
 * @author Flavius
 * @version 1.2
 */
class FrontendComponent extends Component
{
    // default config
    protected $_defaultConfig = [
        'Sitemap' => [
            'exclude' => []
        ]
    ];

    /**
     * {@inheritDoc}
     * @see \Cake\Controller\Component::initialize()
     */
    public function initialize(array $config) {
        parent::initialize($config);

        // we need these
        $controller = $this->getController();
        $session = $controller->getRequest()->getSession();

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
        $controller->loadComponent('Unimatrix/Frontend.Sitemap', $this->_config['Sitemap']);
    }
}
