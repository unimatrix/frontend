<?php

namespace Unimatrix\Frontend\Test\TestCase\Controller\Component;

use Cake\TestSuite\TestCase;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Routing\Router;
use Cake\Utility\Xml;
use Cake\I18n\Time;
use Unimatrix\Frontend\Controller\Component\SitemapComponent;

class SitemapComponentTest extends TestCase
{
    protected $registry;
    protected $component;

    public function setUp() {
        parent::setUp();
        $controller = new Controller();
        $this->registry = new ComponentRegistry($controller);
        $this->component = new SitemapComponent($this->registry);
    }

    protected function buildExpectedXML($urls = [], $style = 'http://localhost/unimatrix/frontend/xsl/sitemap.xsl') {
        $expected = Xml::build(sprintf($this->component->root, $style));
        $this->component->append($expected, Xml::fromArray(['urlset' => ['url' => $urls]]));
        return $expected->asXML();
    }

    protected function buildResultXML($string) {
        return Xml::build($string)->asXML();
    }

    public function testAppendToItsFullest() {
        $test = Xml::build('<urlset><url title="Attribute">fake-value</url></urlset>');
        $test2 = Xml::build('<urlset><url title="Something">hello</url></urlset>');
        $this->component->append($test, $test2);
        $this->assertXmlStringEqualsXmlString($test->asXML(), $test->asXML());
    }

    public function testNoSitemapRoute() {
        Router::connect('/fake');
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML(), $this->buildResultXML($this->component->render()));
    }

    public function testExcludedRoute() {
        $config = [
            'exclude' => [
                '/fake'
            ]
        ];
        $component = new SitemapComponent($this->registry, $config);
        Router::connect('/fake', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => []]);
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML(), $this->buildResultXML($component->render()));
    }

    public function testDefault() {
        $variables = [
            'modified' => '1126650045',
            'frequency' => 'yearly',
            'priority' => '0.1'
        ];
        Router::connect('/fake', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => $variables]);

        $urls = [[
            'loc' => 'http://localhost/fake',
            'lastmod' => (new Time($variables['modified']))->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ]];
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML($urls), $this->buildResultXML($this->component->render()));
    }

    public function testWithoutModified() {
        $variables = [
            'frequency' => 'monthly',
            'priority' => '0.2'
        ];
        Router::connect('/fake', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => $variables]);

        $urls = [[
            'loc' => 'http://localhost/fake',
            'lastmod' => (new Time())->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ]];
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML($urls), $this->buildResultXML($this->component->render()));
    }

    public function testWithModifiedCtrl() {
        $variables = [
            'modified' => 'ctrl',
            'frequency' => 'daily',
            'priority' => '0.3'
        ];
        Router::connect('/fake', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => $variables]);

        $urls = [[
            'loc' => 'http://localhost/fake',
            'lastmod' => (new Time())->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ]];
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML($urls), $this->buildResultXML($this->component->render()));
    }

    public function testWithModifiedCallback() {
        $variables = [
            'modified' => 'callback',
            'frequency' => 'yearly',
            'priority' => '0.4'
        ];
        Router::connect('/fake', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => $variables]);

        $urls = [[
            'loc' => 'http://localhost/fake',
            'lastmod' => (new Time())->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ]];
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML($urls), $this->buildResultXML($this->component->render()));
    }

    public function testDynamicStarRoute() {
        $generated = [
            ['url' => '/fake/anything', 'modified' => (new Time(1227761156))->toAtomString()],
            ['url' => '/fake/whatever/lol/hey', 'modified' => (new Time(1338872267))->toAtomString()]
        ];
        $component = $this->getMockBuilder(SitemapComponent::class)
            ->setConstructorArgs([$this->registry])
            ->setMethods(['_callback'])
            ->getMock();
        $component->expects($this->once())
            ->method('_callback')
            ->will($this->returnValue($generated));

        $variables = [
            'frequency' => 'monthly',
            'priority' => '0.5'
        ];
        Router::connect('/fake/*', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => $variables]);

        $urls = [[
            'loc' => $generated[0]['url'],
            'lastmod' => (new Time($generated[0]['modified']))->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ], [
            'loc' => $generated[1]['url'],
            'lastmod' => (new Time($generated[1]['modified']))->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ]];
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML($urls), $this->buildResultXML($component->render()));
    }

    public function testDynamicSlugRoute() {
        $generated = [
            ['url' => '/fake/first-slug', 'modified' => (new Time(1227761156))->toAtomString()],
            ['url' => '/fake/second-slug', 'modified' => (new Time(1338872267))->toAtomString()]
        ];
        $component = $this->getMockBuilder(SitemapComponent::class)
            ->setConstructorArgs([$this->registry])
            ->setMethods(['_callback'])
            ->getMock();
        $component->expects($this->once())
            ->method('_callback')
            ->will($this->returnValue($generated));

        $variables = [
            'frequency' => 'monthly',
            'priority' => '0.5'
        ];
        Router::connect('/fake/:slug', ['controller' => 'Controller', 'action' => 'action'], ['sitemap' => $variables]);

        $urls = [[
            'loc' => $generated[0]['url'],
            'lastmod' => (new Time($generated[0]['modified']))->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ], [
            'loc' => $generated[1]['url'],
            'lastmod' => (new Time($generated[1]['modified']))->timezone(date_default_timezone_get())->toAtomString(),
            'changefreq' => $variables['frequency'],
            'priority' => $variables['priority']
        ]];
        $this->assertXmlStringEqualsXmlString($this->buildExpectedXML($urls), $this->buildResultXML($component->render()));
    }
}
