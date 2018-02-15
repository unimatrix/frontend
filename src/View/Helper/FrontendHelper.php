<?php

namespace Unimatrix\Frontend\View\Helper;

use Cake\View\Helper;
use Cake\I18n\I18n;
use Cake\Utility\Text;

/**
 * Frontend Helper
 * This helper loads all other necesary stuff for the frontend,
 * it also handles some custom frontend logic and template correction + helper functions
 *
 * @author Flavius
 * @version 1.2
 */
class FrontendHelper extends Helper {
    // load other helpers
    public $helpers = ['Text', 'Html', 'Minify'];

    // default config
    protected $_defaultConfig = [
        'Minify' => [
            'compress' => [
                'html' => true,
                'css' => true,
                'js' => true
            ],
            'config' => [
                'html' => [
                    'doRemoveOmittedHtmlTags' => false
                ],
                'css' => [],
                'js' => []
            ],
            'paths' => [
                'css' => '/cache-css',
                'js' => '/cache-js'
            ]
        ],
        'Form' => ['widgets' => [
            'captcha' => ['Unimatrix/Frontend.Captcha']
        ]]
    ];

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
        $view->loadHelper('Unimatrix/Cake.Minify', $this->_config['Minify']);
        $view->loadHelper('Unimatrix/Cake.Form', $this->_config['Form']);

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

    /**
     * Telephone link shortcut
     *
     * Usage:
     * ---------------------------------------------------
     * $this->Frontend->telephone('123 456 7890');
     *
     * @param string $number
     * @param array $options
     * @return string
     */
    public function telephone($number, $options = []) {
        $link = 'tel:' . str_replace(' ', '', $number);
        return $this->Html->link($number, $link, $options);
    }

    /**
     * Email obfuscator
     *
     * Usage:
     * ---------------------------------------------------
     * $this->Frontend->email('someone@something.com', [
     *     'text' => 'E-mail me right now!',
     *     'subject' => 'Your subject',
     *     'body' => 'Your body',
     *     'cc' => 'cc@something.com',
     *     'bcc' => 'bcc@something.com',
     * ]);
     *
     * @param string $address
     * @param array $options
     * @param array $urloptions
     * @return string
     */
    public function email($address, $options = [], $urloptions = []) {
        // text
        $text = $address;
        if(isset($options['text'])) {
            $text = $options['text'];
            unset($options['text']);
        }

        // build query
        $query = http_build_query($options, false, '&', PHP_QUERY_RFC3986);
        if($query)
            $query = "?{$query}";

        // obfuscate
        $obfuscated = str_rot13($this->Html->link($text, "mailto:{$address}{$query}", $urloptions));
        $unique = Text::uuid();

        // output
        $output = "<span id='{$unique}'>{$this->Minify->inline('script', "
            document.getElementById('{$unique}').innerHTML = '{$obfuscated}'.replace(/[a-zA-Z]/g, function(c) {
                return String.fromCharCode((c <= 'Z' ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
            });
        ", true)}</span>";

        // return output
        return $output;
    }
}
