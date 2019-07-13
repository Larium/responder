<?php

declare(strict_types = 1);

namespace Larium\Responder\Formatter;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use Larium\Bridge\Template\TwigTemplate;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ResponseFactory;

class HtmlFormatterTest extends TestCase
{
    private $formatter;

    private $response;

    public function setUp(): void
    {
        $template = new TwigTemplate(__DIR__ . '/../templates/twig');
        $this->formatter = new HtmlFormatter($template);
        $this->response = (new ResponseFactory())->createResponse();
    }

    public function testShouldFormatHtml(): void
    {
        $payload = new Payload();
        $payload->setStatus(PayloadStatus::FOUND);
        $payload->setOutput([
            'title' => 'A Title',
            'content' => 'A Content',
        ]);

        $payload->setExtras([
            'template' => 'block.html.twig',
        ]);

        $this->response = $this->formatter->format($payload, $this->response);
        $body = $this->response->getBody()->__toString();

        $expected = <<<CONTENT
<h1>A Title</h1>
<p>A Content</p>

CONTENT;
        $this->assertEquals($expected, $body);
    }
}
