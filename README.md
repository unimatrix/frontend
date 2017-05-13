# Unimatrix Frontend

[![Version](https://img.shields.io/packagist/v/unimatrix/frontend.svg?style=flat-square)](https://packagist.org/packages/unimatrix/frontend)
[![Total Downloads](https://img.shields.io/packagist/dt/unimatrix/frontend.svg?style=flat-square)](https://packagist.org/packages/unimatrix/frontend/stats)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/unimatrix/frontend/master/LICENSE)

Frontend for CakePHP 3.4

## Requirements
* PHP >= 7
* CakePHP >= 3.4

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require unimatrix/frontend:~1.0
```

Don't forget to add it to bootstrap
```
Plugin::load('Unimatrix/Frontend');
```

## Configuration

Of course you have to add some things in your `config/app.php`
```
    /**
     * Frontend settings
     *
     * - security - Enables security modules, if ssl is set to true frontend wont load without https
     * - seo - Default SEO values (can be overwritten on a template basis), publishers.facebook should be appid
     * - cookie - The path to the (mandatory by EU) cookie page
     */
    'Frontend' => [
        'security' => [
            'enabled' => true,
            'ssl' => false
        ],
        'seo' => [
            'site' => 'Website.tld',
            'theme' => '#ffffff',
            'title' => __('Website Title'),
            'keywords' => __('website, title'),
            'description' => __('The website description.'),
            'publishers' => [
                'facebook' => '1111111111111111',
                'google' => 'https://plus.google.com/Website',
            ]
        ],
        'cookie' => [
            'message' => 'Our website uses cookies to improve your experience. We'll assume you're ok with this, by navigating further.',
		    'accept' => 'I agree',
		    'details' => 'More details',
            'page' => '/cookies',
        ]
    ],
 ```
