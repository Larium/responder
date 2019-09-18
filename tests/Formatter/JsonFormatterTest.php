<?php

declare(strict_types = 1);

namespace Larium\Responder\Formatter;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use PHPUnit\Framework\TestCase;
use Zend\Diactoros\ResponseFactory;

class JsonFormatterTest extends TestCase
{
    private $formatter;

    private $response;

    public function setUp(): void
    {
        $this->formatter = new JsonFormatter();
        $this->response = (new ResponseFactory())->createResponse();
    }

    public function testShouldFormatJson(): void
    {
        $payload = new Payload();
        $payload->setStatus(PayloadStatus::FOUND);
        $payload->setOutput([
            'title' => 'A Title',
            'content' => 'A Content',
        ]);

        $this->response = $this->formatter->format($payload, $this->response);

        $body = $this->response->getBody()->__toString();

        $expected = '{"title":"A Title","content":"A Content"}';

        $this->assertEquals($expected, $body);
    }
}
