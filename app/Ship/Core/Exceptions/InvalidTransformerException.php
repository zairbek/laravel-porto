<?php

namespace App\Ship\Core\Exceptions;

use App\Ship\Core\Abstracts\Transformers\Transformer;
use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InvalidTransformerException extends Exception
{
    public $httpStatusCode = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;

    public $message = 'Transformers must extended the ' . Transformer::class . ' class.';
}
