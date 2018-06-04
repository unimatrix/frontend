<?php

namespace Unimatrix\Frontend\Test\TestCase\View\Widget;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Cake\Core\Configure;
use Cake\View\StringTemplate;
use Unimatrix\Frontend\View\Widget\CaptchaWidget;
use Unimatrix\Frontend\View\Helper\FrontendHelper;

class CaptchaWidgetTest extends TestCase
{
    protected $templates;
    protected $context;
    protected $data;

    public function setUp() {
        parent::setUp();
        $templates = [
            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}>',
        ];
        $this->templates = new StringTemplate($templates);
        $this->context = $this->getMockBuilder('Cake\View\Form\ContextInterface')->getMock();
		$view = new View(null);
		$helper = new FrontendHelper($view);
		$this->data = [
		    'view' => $helper->getView()
		];
    }

    public function testNoKey() {
        $this->expectException(\RuntimeException::class);

        $input = new CaptchaWidget($this->templates);
        $input->render($this->data, $this->context);
    }

    public function testWidgetHtml() {
        $key = 'my-fake-key';
		Configure::write('Frontend.captcha.key', $key);

        $text = new CaptchaWidget($this->templates);
        $data = $this->data + [
            'hl' => 'ro',
            'size' => 'compact',
            'theme' => 'dark',
            'render' => 'image',
        ];
        $result = $text->render($data, $this->context);

        $expected = [
            ['div' => ['class' => 'captcha-widget']],
                ['div' => [
                    'data-hl' => $data['hl'],
                    'data-size' => $data['size'],
                    'data-theme' => $data['theme'],
                    'data-type' => $data['render'],
                    'data-sitekey' => $key
                ]],
                '/div',
            '/div'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testSecureFields() {
        $input = new CaptchaWidget($this->templates);
        $this->assertEquals(['g-recaptcha-response'], $input->secureFields([]));
    }
}
