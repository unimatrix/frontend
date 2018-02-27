<?php

namespace Unimatrix\Frontend\View\Widget;

use Cake\View\View;
use Cake\View\Widget\BasicWidget;
use Cake\View\Form\ContextInterface;
use Cake\Core\Configure;
use RuntimeException;

/**
 * Captcha
 * This widget is used in conjunction with google recaptcha
 * @see https://www.google.com/recaptcha/admin
 *
 * Example:
 * ---------------------------------------------------------------------------------
 * echo <?= $this->Form->control('captcha', ['type' => 'captcha']); ?>
 * other options you can pass are: (these are the defaults)
 *    'hl' => 'en', // English locale
 *    'size' => 'normal' // either normal or compact
 *    'theme' => 'light', // either light or dark
 *    'render' => 'image', // either image or audio
 *
 * Important: Don't forget about the configuration
 * --------------------------------------------------------
 * 'Frontend' => [
 *     'captcha' => [
 *         'key' => 'your site key',
 *         'secret' => 'your secret key'
 *     ]
 * ],
 *
 * @author Flavius
 * @version 1.2
 */
class CaptchaWidget extends BasicWidget
{
    // extra file prerequisites
    private $prerequisites = [
        // styles
        'style' => [
            'Unimatrix/Frontend.widgets/captcha.css',
        ],

        // javascript
        'script' => [
            'Unimatrix/Frontend.widgets/captcha.js',
        ]
    ];

    /**
     * Load prerequisites
     * @param View $view - The view object
     */
    public function require(View $view) {
        foreach($this->prerequisites as $type => $files)
            $view->Minify->$type($files);
    }

    /**
     * Render a text widget or other simple widget like email/tel/number.
     *
     * This method accepts a number of keys:
     *
     * - `name` The name attribute.
     * - `val` The value attribute.
     * - `escape` Set to false to disable escaping on all attributes.
     *
     * Any other keys provided in $data will be converted into HTML attributes.
     *
     * @param array $data The data to build an input with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string
     */
    public function render(array $data, ContextInterface $context) {
        // defaults
        $data += [
            'name' => '',
            'hl' => 'en',
            'size' => 'normal',
            'theme' => 'light',
            'render' => 'image',
            'templateVars' => []
        ];

        // require prerequisites
        $this->require($data['view']);
        unset($data['view']);

        // render is actually type
        $data['type'] = $data['render'];
        unset($data['render']);

        // add the site key
        $data['sitekey'] = Configure::read('Frontend.captcha.key');
        if(!$data['sitekey'])
            throw new RuntimeException("Captcha Widget: Error in configuration, site key not found");

        // do attributes
        $attrs = $this->_templates->formatAttributes($data, ['name', 'id', 'required']);
        $attrs = 'data-' . trim(str_replace('" ', '" data-', $attrs));

        // render
        return "<div class='captcha-widget'><div {$attrs}></div></div>";
    }

    /**
     * Security above all
     */
    public function secureFields(array $data) {
        return ['g-recaptcha-response'];
    }
}
