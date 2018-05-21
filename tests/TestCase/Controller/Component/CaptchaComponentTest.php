<?php

namespace Unimatrix\Frontend\Test\TestCase\Controller\Component;

use Cake\TestSuite\TestCase;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Http\ServerRequest;
use Cake\Http\Client\Response;
use Unimatrix\Frontend\Controller\Component\CaptchaComponent;

class CaptchaComponentTest extends TestCase
{
    protected $component;

    public function setUp() {
        parent::setUp();
        $controller = new Controller();
        $registry = new ComponentRegistry($controller);
        $this->component = $this->getMockBuilder(CaptchaComponent::class)
            ->setConstructorArgs([$registry])
            ->setMethods(['getClient'])
            ->getMock();
    }

    public function testNoSecret() {
        $this->expectException(\RuntimeException::class);
        $this->component->verify();
    }

    public function testNoRecaptcha() {
        Configure::write('Frontend.captcha.secret', 'my-fake-secret');
        $return = $this->component->verify();
        $this->assertFalse($return);
    }

    public function testPassedCaptcha() {
        Configure::write('Frontend.captcha.secret', 'my-fake-secret');

        // simulate a fake post request
        $request = new ServerRequest([
            'post' => ['g-recaptcha-response' => 'my-fake-response'],
            'environment' => [
                'REQUEST_METHOD' => 'POST'
            ]
        ]);
        $this->component->getController()->setRequest($request);

        // simulate a fake but valid response
        $data = [
            'success' => true,
            'challenge_ts' => '1970-01-01T00:00:00Z',
            'hostname' => 'fake-website.tld'
        ];
        $encoded = json_encode($data);
        $response = new Response([], $encoded);

        // client mock
        $mock = $this->getMockBuilder(Client::class)
            ->setMethods(['post'])
            ->getMock();
        $mock->expects($this->once())
            ->method('post')
            ->will($this->returnValue($response));
        $this->component->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue($mock));

        $this->assertTrue($this->component->verify());
    }
}
