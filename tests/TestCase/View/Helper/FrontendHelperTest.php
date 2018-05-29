<?php

namespace Unimatrix\Frontend\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\I18n\I18n;
use Cake\View\View;
use Cake\View\Helper\TextHelper;
use Cake\View\Helper\HtmlHelper;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Unimatrix\Cake\View\Helper\MinifyHelper;
use Unimatrix\Cake\View\Helper\FormHelper;
use Unimatrix\Frontend\View\Helper\FrontendHelper;

class FrontendHelperTest extends TestCase
{
    protected $helper;

	public function setUp() {
		parent::setUp();
		$view = new View(null);
		$this->helper = new FrontendHelper($view);
	}

    public function testAdditionalHelpersLoaded() {
        $this->assertInstanceOf(TextHelper::class, $this->helper->Text);
        $this->assertInstanceOf(HtmlHelper::class, $this->helper->Html);
        $this->assertInstanceOf(MinifyHelper::class, $this->helper->Minify);
    }

    public function testRequiredHelpersLoaded() {
        $view = $this->helper->getView();

        $this->assertInstanceOf(MinifyHelper::class, $view->Minify);
        $this->assertInstanceOf(FormHelper::class, $view->Form);
    }

    public function testSetLocale() {
        $locale = 'ro_RO';
        $request = new ServerRequest();
        $session = $request->getSession();
        $session->write('App.locale', $locale);

		$view = new View($request);
		$helper = new FrontendHelper($view);
		$view = $helper->getView();

        $this->assertEquals($locale, I18n::getLocale());
        $this->assertEquals($locale, $view->get('locale'));
        $this->assertEquals(explode('_', $locale)[0], $view->get('language'));
    }

    public function testHelperFunctions() {
        $expected = "A very long string indeed, it has to be because we're checking to see if truncation will happen to it because our search engines require it no...";
        $result = $this->helper->seoDescription("A very long string indeed, it has to be because we're checking to see if truncation will happen to it because our search engines require it no longer than 150 characters.");
        $this->assertEquals($expected, $result);

        $width = 200;
        $height = 300;
        $resource = fopen('php://memory', 'w+');
        $image = imagecreate($width, $height);
        imagecolorallocate($image, 255, 0, 0);
        imagepng($image, $resource);
        imagedestroy($image);
        fseek($resource, 0);
        $file = 'data://image/png;base64,' . base64_encode(stream_get_contents($resource));
        $this->assertEquals(getimagesize($file), $this->helper->identityInfo($file));
        $this->assertEquals($width, $this->helper->identityInfo($file, 'width'));
        $this->assertEquals($height, $this->helper->identityInfo($file, 'height'));

        Router::connect('/');
        $tel = '+40 123 45 67 89';
        $expected = ['a' => ['href' => 'preg:/tel\:[\+0-9]+/'], $tel, '/a'];
        $result = $this->helper->telephone($tel);
        $this->assertHtml($expected, $result);

        $expected = '/\<span id\=\'([a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89aAbB][a-f0-9]{3}-[a-f0-9]{12})\'\>\<script\>document\.getElementById\(\'([a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89aAbB][a-f0-9]{3}-[a-f0-9]{12})\'\)\.innerHTML\=\'\<n uers\=\"znvygb\:fbzrbar\@fbzrguvat\.pbz\?fhowrpg\=Lbhe\%20fhowrpg\&nzc\;obql\=Lbhe\%20obql\&nzc\;pp\=pp\%40fbzrguvat\.pbz\&nzc\;opp\=opp\%40fbzrguvat\.pbz\"\>R\-znvy zr evtug abj\!\<\/n\>\'\.replace\(\/\[a\-zA\-Z\]\/g\,function\(c\)\{return String\.fromCharCode\(\(c\<\=\'Z\'\?90\:122\)\>\=\(c\=c\.charCodeAt\(0\)\+13\)\?c\:c\-26\)\}\)\<\/script\>\<\/span\>/';
        $result = $this->helper->email('someone@something.com', [
            'text' => 'E-mail me right now!',
            'subject' => 'Your subject',
            'body' => 'Your body',
            'cc' => 'cc@something.com',
            'bcc' => 'bcc@something.com',
        ]);
        $this->assertRegExp($expected, $result);
    }
}
