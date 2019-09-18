<?php

declare(strict_types = 1);

namespace Larium\Responder\Formatter;

use Aura\Payload_Interface\ReadablePayloadInterface;
use Larium\Responder\Formatter\Formatter;
use Psr\Http\Message\ResponseInterface;

class JsonFormatter implements Formatter
{
    public function format(
        ReadablePayloadInterface $payload,
        ResponseInterface $response
    ): ResponseInterface {
        $content = '';
        $output = $payload->getOutput();
        if ((is_object($output) && $output instanceof \JsonSerializable)
            || is_array($output)
        ) {
            $content = json_encode($output);
        }

        if (is_string($output)) {
            $content = $output;
        }

        $response->getBody()->write($content);

        return $response->withHeader('Content-Type', 'application/json')
                        ->withHeader('Content-Length', (string) strlen($content));
    }
}
