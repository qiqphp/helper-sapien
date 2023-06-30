<?php
declare(strict_types=1);

namespace Qiq\Helper\Sapien;

use Sapien\Request as SapienRequest;
use Sapien\Response\FileResponse;
use Sapien\Response\JsonResponse;

/**
 * @mixin SapienRequest
 */
class Request
{
    protected SapienRequest $request;

    public function __construct(SapienRequest $request = null)
    {
        $this->set($request ?? new SapienRequest());
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
