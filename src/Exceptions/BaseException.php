<?php

namespace Jeffpereira\RealEstate\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BaseException extends Exception
{
    protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    public function render($request)
    {
        return response(
            ['success' => false, 'error' => true, 'message' => $this->getMessage()],
            $this->statusCode
        );
    }
}
