<?php

// assign seo defaults
$this->element('Unimatrix/Frontend.seo');

// all these can be modified through $this->assign('... from template views
$site = $this->fetch('site') ? $this->fetch('site') : ($this->fetch('seo_site') ? $this->fetch('seo_site') : 'Untitled Project');
$theme = $this->fetch('theme') ? $this->fetch('theme') : ($this->fetch('seo_theme') ? $this->fetch('seo_theme') : '#ffffff');
$title = $this->fetch('title') ? $this->fetch('title') : ($this->fetch('seo_title') ? $this->fetch('seo_title') : $site);
$keywords = $this->fetch('keywords') ? $this->fetch('keywords') : ($this->fetch('seo_keywords') ? $this->fetch('seo_keywords') : $site);
$description = $this->fetch('description') ? $this->Frontend->seoDescription($this->fetch('description')) : ($this->fetch('seo_description') ? $this->fetch('seo_description') : $site);
$canonical = $this->fetch('canonical') ? $this->fetch('canonical') : $this->Url->build(null, true);
$identity = $this->fetch('identity') ? $this->fetch('identity') : $this->Url->build('/img/identity.png', true);
$identityWidth = $this->fetch('identityWidth') ? $this->fetch('identityWidth') : ($this->fetch('identity') ? $this->Frontend->identityInfo($this->fetch('identity'))[0] : 600);
$identityHeight = $this->fetch('identityHeight') ? $this->fetch('identityHeight') : ($this->fetch('identity') ? $this->Frontend->identityInfo($this->fetch('identity'))[1] : 314);
$classification = $this->fetch('classification') ? $this->fetch('classification') : 'website';

// charset
echo $this->Html->charset();

// title
echo "<title>{$title} - {$site}</title>";

// icon and meta
echo $this->Html->meta('icon');
echo $this->fetch('meta');

// viewport
// echo $this->Html->meta('viewport', 'width=1000, initial-scale=1.0'); // static page
echo $this->Html->meta('viewport', 'width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no'); // responsive page

// android theme fancy-ness
echo $this->Html->meta('theme-color', $theme);

// SEO
echo $this->Html->meta('keywords', $keywords);
echo $this->Html->meta('description', $description);
echo $this->Html->meta(['rel' => 'canonical', 'link' => $canonical]);

// Open Graph
echo $this->Html->meta(['property' => 'og:locale', 'content' => $locale]);
echo $this->Html->meta(['property' => 'og:type', 'content' => $classification]);
echo $this->Html->meta(['property' => 'og:title', 'content' => $title]);
echo $this->Html->meta(['property' => 'og:site_name', 'content' => $site]);
echo $this->Html->meta(['property' => 'og:url', 'content' => $canonical]);
echo $this->Html->meta(['property' => 'og:description', 'content' => $description]);
echo $this->Html->meta(['property' => 'og:image', 'content' => $identity]);
echo $this->Html->meta(['property' => 'og:image:width', 'content' => $identityWidth]);
echo $this->Html->meta(['property' => 'og:image:height', 'content' => $identityHeight]);

// facebook app id
if($this->fetch('publishers_facebook'))
    echo $this->Html->meta(['property' => 'fb:app_id', 'content' => $this->fetch('publishers_facebook')]);

// google page
if($this->fetch('publishers_google'))
    echo $this->Html->meta(['rel' => 'publisher', 'link' => $this->fetch('publishers_google')]);

// external
if(isset($external))
    echo $this->Html->css($external);

// css (must be compressed)
if(!isset($internal))
    $internal = [];

// combine and output
$this->Minify->style($internal);
$this->Minify->fetch('style');

// other styles
echo $this->fetch('css');
