<?php

use Cake\Core\Configure;
use Cake\Utility\Inflector;

// default
$seo = Configure::read('Frontend.seo');

// loop
foreach($seo as $idx => $one) {
    if(is_array($one)) {
        foreach($one as $jdx => $two)
            designate($this, $jdx, $two, $idx);

    } else designate($this, $idx, $one);
}

// designate
function designate($self, $key, $value, $prefix = 'seo') {
    $self->assign($prefix . '_' . $key, $value);
}

// don't use cake default template path as title
if(Inflector::humanize($this->templatePath) == $this->fetch('title'))
    $this->assign('title', null);
