<?php

declare(strict_types = 1);

namespace Larium\Responder;

use Aura\Payload_Interface\PayloadStatus;
use Aura\Payload_Interface\ReadablePayloadInterface;
use Larium\Bridge\Template\Template;
use Larium\Responder\Formatter\Formatter;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Teapot\StatusCode;

class Responder
{
    private const STATUS = [
        PayloadStatus::ACCEPTED => StatusCode::ACCEPTED,
        PayloadStatus::AUTHENTICATED => StatusCode::OK,
        PayloadStatus::AUTHORIZED => StatusCode::OK,
        PayloadStatus::CREATED => StatusCode::CREATED,
        PayloadStatus::DELETED => StatusCode::OK,
        PayloadStatus::ERROR => StatusCode::INTERNAL_SERVER_ERROR,
        PayloadStatus::FAILURE => StatusCode::BAD_REQUEST,
        PayloadStatus::FOUND => StatusCode::OK,
        PayloadStatus::NOT_ACCEPTED => StatusCode::NOT_ACCEPTABLE,
        PayloadStatus::NOT_AUTHENTICATED => StatusCode::UNAUTHORIZED,
        PayloadStatus::NOT_AUTHORIZED => StatusCode::FORBIDDEN,
        PayloadStatus::NOT_CREATED => StatusCode::NOT_MODIFIED,
        PayloadStatus::NOT_DELETED => StatusCode::NOT_MODIFIED,
        PayloadStatus::NOT_FOUND => StatusCode::NOT_FOUND,
        PayloadStatus::NOT_UPDATED => StatusCode::NOT_MODIFIED,
        PayloadStatus::NOT_VALID => StatusCode::BAD_REQUEST,
        PayloadStatus::PROCESSING => StatusCode::CONTINUING,
        PayloadStatus::SUCCESS => StatusCode::OK,
        PayloadStatus::UPDATED => StatusCode::OK,
        PayloadStatus::VALID => StatusCode::OK,
    ];

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ReadablePayloadInterface|null
     */
    private $payload;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var Formatter
     */
    private $formatter;

    public function __construct(Formatter $formatter, ResponseFactoryInterface $responseFactory)
    {
        $this->formatter = $formatter;
        $this->response = $responseFactory->createResponse();
    }

    public function __invoke(
        ServerRequestInterface $request,
        ReadablePayloadInterface $payload = null
    ): ResponseInterface {
        $this->request = $request;
        $this->payload = $payload;
        $this->setStatusForPayload();

        if ($this->payload) {
            $this->response = $this->formatter->format($this->payload, $this->response);
        }

        return $this->response;
    }

    private function setStatusForPayload(): void
    {
        if (!$this->payload) {
            $this->response = $this->response->withStatus(StatusCode::NO_CONTENT);
            return;
        }

        if (array_key_exists($this->payload->getStatus(), self::STATUS)) {
            $this->response = $this->response->withStatus(self::STATUS[$this->payload->getStatus()]);
        }
    }
}
