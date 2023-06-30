<?php
namespace Qiq\Helper\Sapien;

use Sapien\Response as SapienResponse;
use Sapien\Response\FileResponse;
use Sapien\Response\Header;
use Sapien\Response\JsonResponse;
use SplFileInfo;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    protected SapienTemplate $template;

    public function setUp() : void
    {
        $this->template = SapienTemplate::new(
            paths: [__DIR__ . '/templates/'],
            helpers: new SapienHelpers()
        );
    }

    public function test() : void
    {
        $this->template->setView('text');
        $response = $this->template->response()->render($this->template);
        $this->assertInstanceOf(SapienResponse::CLASS, $response);
        $this->assertSame('hello world', $response->getContent());


        /** @var Header */
        $header = $response->getHeader('content-type');
        $this->assertSame('text/plain', (string) $header);
    }

    public function testSetFile() : void
    {
        $file = __DIR__ . '/templates/text.php';
        $this->template->setData(['file' => __DIR__ . '/templates/text.php']);
        $this->template->setView('file');
        $response = $this->template->response()->render($this->template);
        $this->assertInstanceOf(FileResponse::CLASS, $response);

        /** @var SplFileInfo */
        $content = $response->getContent();
        $this->assertSame($file, $content->getRealPath());
    }

    public function testSetJson() : void
    {
        $data = ['foo' => 'bar'];
        $this->template->setData($data);
        $this->template->setView('json');
        $response = $this->template->response()->render($this->template);
        $this->assertInstanceOf(JsonResponse::CLASS, $response);
        $this->assertEquals($data, $response->getContent());
    }
}
