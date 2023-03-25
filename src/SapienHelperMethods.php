<?php
declare(strict_types=1);

namespace Qiq\Helper\Sapien;

trait SapienHelperMethods
{
	public function request(): Request
	{
		return $this->get(Request::class);
	}

	public function response(): Response
	{
		return $this->get(Response::class);
	}
}
