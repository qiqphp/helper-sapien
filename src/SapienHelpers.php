<?php
declare(strict_types=1);

namespace Qiq\Helper\Sapien;

trait SapienHelpers
{
	public function request(): Request
	{
		return $this->getObject(Request::class);
	}

	public function response(): Response
	{
		return $this->getObject(Response::class);
	}
}
