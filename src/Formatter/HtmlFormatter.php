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

    public function format(ReadablePayloadInterface $payload): string
    {
        $params = $payload->getOutput();
        $extra = $payload->getExtras();
        $params['input'] = $payload->getInput();

        return $this->template->render($extra['template'], $params);
    }

    public function formatResponse(ResponseInterface $response): ResponseInterface
    {
        return $response->withHeader('Content-Type', 'text/html');
    }
}
