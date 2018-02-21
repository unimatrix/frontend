<?php

use Cake\Core\Configure;

// inline stuff
$this->Minify->inline('script', "
    var WEBROOT = \"{$this->Url->build('/', true)}\";
    var DEV_ENV = ". (Configure::read('debug') ? 'true' : 'false') .";
    var EU_COOKIE = " . (Configure::check('Frontend.cookie') ? json_encode([
        'url' => $this->Url->build(Configure::read('Frontend.cookie.url')),
        'message' => __d('Unimatrix/frontend', "Our website uses cookies to improve your experience. We'll assume you're ok with this, by navigating further."),
        'accept' => __d('Unimatrix/frontend', "I agree"),
        'details' => __d('Unimatrix/frontend', "More details")
    ]) : 'false') . ";
");

// external
if(isset($external))
    echo $this->Html->script($external);

// internal (must be compressed)
if(!isset($internal))
    $internal = [];

// combine and output
$this->Minify->script($internal);
$this->Minify->fetch('script');

// other scripts
echo $this->fetch('script');
