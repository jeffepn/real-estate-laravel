<?php

namespace Jeffpereira\RealEstate\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class CannotDeleteSituationRelationshipsException extends BaseException
{
    protected $message = 'Não é possivel remover uma situação, com imóveis relacionados a ela.';
    protected $statusCode = Response::HTTP_BAD_REQUEST;
}
