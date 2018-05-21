<?php

namespace Unimatrix\Frontend\Test\TestCase\Controller\Component;

use Cake\TestSuite\TestCase;
use Cake\I18n\I18n;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Component\SecurityComponent;
use Unimatrix\Frontend\Controller\Component\FrontendComponent;
use Unimatrix\Frontend\Controller\Component\CaptchaComponent;
use Unimatrix\Frontend\Controller\Component\SitemapComponent;

class FrontendComponentTest extends TestCase
{
    public function testInitialize() {
        Configure::write('Frontend.security.enabled', true);
        Configure::write('Frontend.security.ssl', true);

        $locale = 'ro_RO';
        $request = new ServerRequest();
        $session = $request->getSession();
        $session->write('App.locale', $locale);

        $controller = new Controller();
        $controller->setRequest($request);
        $registry = new ComponentRegistry($controller);
        $component = new FrontendComponent($registry);

        $this->assertInstanceOf(FrontendComponent::class, $component);
        $this->assertInstanceOf(SecurityComponent::class, $component->getController()->Security);
        $this->assertInstanceOf(CaptchaComponent::class, $component->getController()->Captcha);
        $this->assertInstanceOf(SitemapComponent::class, $component->getController()->Sitemap);

        $this->assertEquals($locale, I18n::getLocale());
        $this->assertArraySubset(['*'], $component->getController()->Security->getConfig('requireSecure'));
    }
}
