# Unimatrix Frontend

[![Version](https://img.shields.io/packagist/v/unimatrix/frontend.svg?style=flat-square)](https://packagist.org/packages/unimatrix/frontend)
[![Total Downloads](https://img.shields.io/packagist/dt/unimatrix/frontend.svg?style=flat-square)](https://packagist.org/packages/unimatrix/frontend/stats)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/unimatrix/frontend/master/LICENSE)

Frontend for CakePHP 3.5

## Requirements
* PHP >= 7
* CakePHP >= 3.5

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require unimatrix/frontend:^2.0
```

Don't forget to add it to bootstrap
```
Plugin::load('Unimatrix/Frontend', ['bootstrap' => true]);
```

## Configuration

Of course you have to add some things in your `config/app.php`
```
    /**
     * Frontend settings
     *
     * - security - Enables security modules, if ssl is set to true frontend wont load without https
     * - seo - Default SEO values (can be overwritten on a template basis), publishers.facebook should be appid
     * - cookie - The default info for the (mandatory by EU) cookie message
     */
    'Frontend' => [
        'security' => [
            'enabled' => true,
            'ssl' => false,
            'exceptions' => [ // skip CSRF verifications for these requests
                ['controller' => 'API', 'action' => 'batch'], // skip the batch action from the API controller
                ['controller' => 'Amazon'] // skip the whole amazon controller
            ]
        ],
        'seo' => [
            'site' => 'Website.tld',
            'theme' => '#ffffff',
            'title' => 'Website Title',
            'keywords' => 'website, title',
            'description' => 'The website description.',
            'publishers' => [
                'facebook' => '1111111111111111', // app id
                'google' => 'https://plus.google.com/Website',
            ]
        ],
        'cookie' => [
            'url' => '/cookies', // or array ['controller' => 'Pages', 'action' => 'cookies']
        ]
    ],

    /**
     * Google reCaptcha
     */
    'Captcha' => [
        'key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'secret' => 'yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy'
    ],
 ```
