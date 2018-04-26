<?php

use Cake\Core\Configure;
use Cake\Utility\Inflector;

// default
$seo = Configure::read('Frontend.seo');

// loop
foreach($seo as $idx => $one) {
    if(is_array($one)) {
        foreach($one as $jdx => $two)
            $this->assign($idx . '_' . $jdx, $two);
    } else $this->assign('seo_' . $idx, $one);
}

// don't use cake default template path as title
if(Inflector::humanize($this->templatePath) == $this->fetch('title'))
    $this->assign('title', null);
