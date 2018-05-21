<?php

namespace Unimatrix\Frontend\Test\TestCase;

use Cake\TestSuite\TestCase;
use Cake\Core\Configure;
use Cake\Http\MiddlewareQueue;
use Unimatrix\Frontend\Http\Middleware\CsrfProtectionMiddleware;
use Unimatrix\Frontend\Plugin;

class PluginTest extends TestCase
{
    protected $server = null;
    protected $plugin;
    protected $stack;

    public function setUp() {
        parent::setUp();
        $this->server = $_SERVER;
        $this->stack = new MiddlewareQueue();
        $this->plugin = new Plugin();
    }

    public function tearDown() {
        parent::tearDown();
        $_SERVER = $this->server;
    }

    public function testPluginName() {
        $this->assertEquals('Unimatrix/Frontend', $this->plugin->getName());
    }

    public function testMiddlewareLoadAsBackend() {
        $_SERVER['REQUEST_URI'] = '/backend';
        Configure::write('Backend', true);
        $middleware = $this->plugin->middleware($this->stack);

        $this->assertInstanceOf(MiddlewareQueue::class, $middleware);
        $this->assertCount(0, $middleware);
    }

    public function testMiddlewareLoadAsDebugKit() {
        $_SERVER['REQUEST_URI'] = '/debug_kit';
        $middleware = $this->plugin->middleware($this->stack);

        $this->assertInstanceOf(MiddlewareQueue::class, $middleware);
        $this->assertCount(0, $middleware);
    }

    public function testMiddlewareLoadAsAlternateDebugKit() {
        $_SERVER['REQUEST_URI'] = '/debug-kit';
        $middleware = $this->plugin->middleware($this->stack);

        $this->assertInstanceOf(MiddlewareQueue::class, $middleware);
        $this->assertCount(0, $middleware);
    }

    public function testCsrfProtectionMiddlewareLoaded() {
        Configure::write('Frontend.security.enabled', true);
        $middleware = $this->plugin->middleware($this->stack);

        $this->assertInstanceOf(MiddlewareQueue::class, $middleware);
        $this->assertInstanceOf(CsrfProtectionMiddleware::class, $middleware->get(0));
        $this->assertCount(1, $middleware);
    }
}
