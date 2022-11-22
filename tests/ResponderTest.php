<?php

declare(strict_types = 1);

namespace Larium\Responder;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use Larium\Bridge\Template\TwigTemplate;
use Larium\Responder\Formatter\HtmlFormatter;
use PHPUnit\Framework\TestCase;
use Teapot\StatusCode;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;

class ResponderTest extends TestCase
{
    private $responder;

    private $request;

    public function setUp(): void
    {
        $template = new TwigTemplate(__DIR__ . '/templates/twig');
        $formatter = new HtmlFormatter($template);
        $this->responder = new Responder($formatter, new ResponseFactory());
        $this->request = (new ServerRequestFactory())->createServerRequest('GET', 'https://www.example.com/tests');
    }

    public function testShouldCreateResponse(): void
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

        $response = $this->responder->__invoke($this->request, $payload);

        $this->assertEquals(StatusCode::OK, $response->getStatusCode());
    }

    public function testShouldCreateNoContentResponse(): void
    {
        $response = $this->responder->__invoke($this->request);

        $this->assertEquals(StatusCode::NO_CONTENT, $response->getStatusCode());

        $this->assertEmpty($response->getBody()->__toString());
    }
}
