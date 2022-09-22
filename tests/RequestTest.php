<?php
namespace Qiq\Helper\Sapien;

use Qiq\Template;
use Sapien\Request as SapienRequest;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    protected Template $template;

    public function setUp() : void
    {
        $this->template = Template::new([__DIR__ . '/templates/']);
        Request::register('request', $this->template);
    }

    public function test()
    {
        $this->assertInstanceOf(Request::CLASS, $this->template->request());
        $this->assertInstanceOf(SapienRequest::CLASS, $this->template->request()->get());
        $this->assertNotEmpty($this->template->request()->server);
    }
}
