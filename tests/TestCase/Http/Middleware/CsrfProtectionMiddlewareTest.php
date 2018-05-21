<?php

namespace Unimatrix\Frontend\Test\TestCase\Http\Middleware;

use Cake\TestSuite\TestCase;
use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Unimatrix\Frontend\Http\Middleware\CsrfProtectionMiddleware;

class CsrfProtectionMiddlewareTest extends TestCase
{
    protected $request;
    protected $response;

    public function setUp() {
        parent::setUp();
        $this->request = new ServerRequest([
            'environment' => [
                'REQUEST_METHOD' => 'GET'
            ]
        ]);
        $this->response = new Response();
    }

    public function testPluginAndControllerAndActionSkip() {
        $skip = [
            'plugin' => 'Evil',
            'controller' => 'Control',
            'action' => 'edit'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testPluginAndControllerSkip() {
        $skip = [
            'plugin' => 'Evil',
            'controller' => 'Control'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testPluginAndActionSkip() {
        $skip = [
            'plugin' => 'Evil',
            'action' => 'edit'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testPluginSkip() {
        $skip = [
            'plugin' => 'Evil'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testControllerAndActionSkip() {
        $skip = [
            'controller' => 'Control',
            'action' => 'edit'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testControllerSkip() {
        $skip = [
            'controller' => 'Control'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testActionSkip() {
        $skip = [
            'action' => 'edit'
        ];
        Configure::write('Frontend.security.skip', [$skip]);
        foreach($skip as $idx => $value)
            $this->request = $this->request->withParam($idx, $value);

        $next = function ($req, $res) {
            $this->assertFalse($req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }

    public function testNoSkip() {
        $next = function ($req, $res) {
            $this->assertInternalType('string', $req->getParam('_csrfToken'));
        };
        $middleware = new CsrfProtectionMiddleware();
        $middleware($this->request, $this->response, $next);
    }
}
