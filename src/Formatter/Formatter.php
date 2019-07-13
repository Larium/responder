<?php

declare(strict_types = 1);

namespace Larium\Responder\Formatter;

use Aura\Payload_Interface\ReadablePayloadInterface;

interface Formatter
{
    public function format(ReadablePayloadInterface $payload): string;
}
