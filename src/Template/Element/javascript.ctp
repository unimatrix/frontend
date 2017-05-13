<?php

use Cake\Core\Configure;

// inline stuff
$this->Minify->inline('script', "
    var WEBROOT = \"{$this->Url->build('/', true)}\";
    var DEV_ENV = ". (Configure::read('debug') ? 'true' : 'false') .";
    var EU_COOKIE = " . json_encode(Configure::read('Frontend.cookie')) . ";
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
