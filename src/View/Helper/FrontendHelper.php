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
 * @version 1.0
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

        // load required helpers
        $this->getView()->loadHelper('Unimatrix/Cake.Minify');
        $this->getView()->loadHelper('Unimatrix/Frontend.Obfuscate');
        $this->getView()->loadHelper('Unimatrix/Frontend.Form', ['widgets' => [
            'captcha' => ['Unimatrix/Frontend.Captcha'],
        ]]);

        // send locale and language to views
        $locale = I18n::getLocale();
        $this->getView()->set('locale', $locale);
        $this->getView()->set('language', explode('_', $locale)[0]);
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
