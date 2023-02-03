<?php
namespace Qiq\Helper\Sapien;

use Qiq\HelperLocator;
use Qiq\Template;
use Sapien\Request as SapienRequest;
use Sapien\Response\FileResponse;
use Sapien\Response\JsonResponse;

/**
 * @mixin SapienRequest
 */
class Request
{
    public static function register(
        string $name,
        Template|HelperLocator $helperLocator,
        SapienRequest $request = null
    ) : void
    {
        if ($helperLocator instanceof Template) {
            $helperLocator = $helperLocator->getHelperLocator();
        }

        $helperLocator->set($name, function () use ($request) {
            return new static($request ?? new SapienRequest());
        });
    }

    protected SapienRequest $request;

    public function __construct(SapienRequest $request = null)
    {
        $this->set($request ?? new SapienRequest());
    }

    public function __invoke() : static
    {
        return $this;
    }

    public function __get(string $prop) : mixed
    {
        return $this->request->$prop;
    }

    public function set(SapienRequest $request) : static
    {
        $this->request = $request;
        return $this;
    }

    public function get() : SapienRequest
    {
        return $this->request;
    }
}
