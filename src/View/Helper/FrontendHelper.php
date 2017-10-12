<?php

namespace Unimatrix\Frontend\View\Helper;

use Cake\View\Helper;
use Cake\I18n\I18n;

/**
 * Frontend Helper
 * This helper loads all other necesary stuff for the frontend,
 * it also handles some custom frontend logic and template correction
 *
 * @author Flavius
 * @version 1.1
 */
class FrontendHelper extends Helper {
    // load other helpers
    public $helpers = ['Text'];

    /**
     * {@inheritDoc}
     * @see \Cake\View\Helper::initialize()
     */
    public function initialize(array $config) {
        parent::initialize($config);

        // we need these
        $view = $this->getView();
        $session = $this->request->getSession();

        // load required helpers
        $view->loadHelper('Unimatrix/Cake.Minify');
        $view->loadHelper('Unimatrix/Frontend.Obfuscate');
        $view->loadHelper('Unimatrix/Frontend.Form', ['widgets' => [
            'captcha' => ['Unimatrix/Frontend.Captcha'],
        ]]);

        // set locale based on session
        // this is here because error doesnt pass through app controller where 
        // we load the frontend component, so if that never gets loaded do it here
        if($session->check('App.locale'))
            I18n::setLocale($session->read('App.locale'));

        // send locale and language to views
        $locale = I18n::getLocale();
        $view->set('locale', $locale);
        $view->set('language', explode('_', $locale)[0]);
    }

    /**
     * Generate a SEO description from a string
     * @param string $text
     */
    public function seoDescription($text) {
        $text = strip_tags($text);
        $text = html_entity_decode($text);

        return $this->Text->truncate($text, 150, ['exact' => false]);
    }
}
