<?php

declare(strict_types = 1);

namespace Larium\Responder\Formatter;

use Aura\Payload_Interface\ReadablePayloadInterface;
use Larium\Bridge\Template\Template;
use Psr\Http\Message\ResponseInterface;

class HtmlFormatter implements Formatter
{
    private $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }

    public function format(
        ReadablePayloadInterface $payload,
        ResponseInterface $response
    ): ResponseInterface {
        $params = $payload->getOutput();
        $extra = $payload->getExtras();
        $params['input'] = $payload->getInput();

        $content = $this->template->render($extra['template'], $params);
        $response->getBody()->write($content);

        return $response->withHeader('Content-Type', 'text/html')
                        ->withHeader('Content-Length', (string) strlen($content));
    }
}
