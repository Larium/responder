<?php

declare(strict_types = 1);

namespace Larium\Responder\Formatter;

use Aura\Payload_Interface\ReadablePayloadInterface;
use Psr\Http\Message\ResponseInterface;

interface Formatter
{
    public function format(
        ReadablePayloadInterface $payload,
        ResponseInterface $response
    ): ResponseInterface;
}
