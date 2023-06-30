<?php
namespace Qiq\Helper\Sapien;

use Sapien\Request as SapienRequest;

class RequestTest extends \PHPUnit\Framework\TestCase
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
        $this->assertInstanceOf(Request::CLASS, $this->template->request());
        $this->assertInstanceOf(SapienRequest::CLASS, $this->template->request()->get());
        $this->assertNotEmpty($this->template->request()->server);
    }
}
