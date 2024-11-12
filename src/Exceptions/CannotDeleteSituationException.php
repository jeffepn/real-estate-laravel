<?php

namespace Jeffpereira\RealEstate\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class CannotDeleteSituationException extends BaseException
{
    protected $message = 'Não foi possível excluir a situação de imóvel.';
    protected $statusCode = Response::HTTP_BAD_REQUEST;
}
