<?php
declare(strict_types=1);

namespace Qiq\Helper\Sapien;

use Qiq\Template;
use Sapien\Response as SapienResponse;
use Sapien\Response\FileResponse;
use Sapien\Response\JsonResponse;
use SplFileObject;

/**
 * @mixin SapienResponse
 */
class Response
{
    protected SapienResponse $response;

    public function __construct(SapienResponse $response = null)
    {
    	$this->set($response ?? new SapienResponse());
    }

    /**
     * @param mixed[] $args
     */
    public function __call(string $func, array $args) : mixed
    {
        return $this->response->$func(...$args);
    }

    public function set(SapienResponse $response) : static
    {
        $this->response = $response;
        return $this;
    }

    public function get() : SapienResponse
    {
        return $this->response;
    }

    public function render(Template $template) : SapienResponse
    {
        $content = $template();

        if ($this->response->getContent() === null) {
            $this->response->setContent($content);
        }

        return $this->get();
    }

    public function setJson(
        mixed $value,
        string $type = null,
        int $flags = null,
        int $depth = null,
    ) : static
    {
        $response = new JsonResponse();
        $this->copyInto($response);
        $response->setJson(
            $value,
            $type,
            $flags,
            $depth,
        );
        return $this->set($response);
    }

    public function setFile(
        string|SplFileObject $file,
        string $disposition = null,
        string $name = null,
        string $type = null,
        string $encoding = null,
    ) : static
    {
        $response = new FileResponse();
        $this->copyInto($response);
        $response->setFile(
            $file,
            $disposition,
            $name,
            $type,
            $encoding
        );
        return $this->set($response);
    }

    protected function copyInto(SapienResponse $newResponse) : void
    {
        $newResponse->setVersion($this->response->getVersion());
        $newResponse->setCode($this->response->getCode());
        $newResponse->setHeaders($this->response->getHeaders());
        $newResponse->setCookies($this->response->getCookies());
        $newResponse->setHeaderCallbacks($this->response->getHeaderCallbacks());
    }
}
