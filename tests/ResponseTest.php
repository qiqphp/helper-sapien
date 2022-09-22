<?php
namespace Qiq\Helper\Sapien;

use Qiq\Template;
use Sapien\Response as SapienResponse;
use Sapien\Response\FileResponse;
use Sapien\Response\JsonResponse;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    protected Template $template;

    public function setUp() : void
    {
        $this->template = Template::new([__DIR__ . '/templates/']);
        Response::register('response', $this->template);
    }

    public function test()
    {
        $this->template->setView('text');
        $response = $this->template->response()->render($this->template);
        $this->assertInstanceOf(SapienResponse::CLASS, $response);
        $this->assertSame('hello world', $response->getContent());
        $this->assertSame('text/plain', $response->getHeader('content-type')->value);
    }

    public function testSetFile()
    {
        $file = __DIR__ . '/templates/text.php';
        $this->template->setData(['file' => __DIR__ . '/templates/text.php']);
        $this->template->setView('file');
        $response = $this->template->response()->render($this->template);
        $this->assertInstanceOf(FileResponse::CLASS, $response);
        $this->assertSame($file, $response->getContent()->getRealPath());
    }

    public function testSetJson()
    {
        $data = (object) ['foo' => 'bar'];
        $this->template->setData($data);
        $this->template->setView('json');
        $response = $this->template->response()->render($this->template);
        $this->assertInstanceOf(JsonResponse::CLASS, $response);
        $this->assertEquals($data, $response->getContent());
    }
}
