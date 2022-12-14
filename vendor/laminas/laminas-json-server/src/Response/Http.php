<?php

declare(strict_types=1);

namespace Laminas\Json\Server\Response;

use Laminas\Json\Server\Response as JsonResponse;

use function header;
use function headers_sent;

class Http extends JsonResponse
{
    /**
     * Emit JSON
     *
     * Send appropriate HTTP headers.
     *
     * If no Id, then return an empty string.
     *
     * @return string
     */
    public function toJson()
    {
        $this->sendHeaders();

        if (! $this->isError() && null === $this->getId()) {
            return '';
        }

        return parent::toJson();
    }

    /**
     * Send headers
     *
     * If headers are already sent, do nothing.
     *
     * If null ID, send HTTP 204 header.
     *
     * Otherwise, send content type header based on content type of service
     * map.
     *
     * @return void
     */
    public function sendHeaders()
    {
        if (headers_sent()) {
            return;
        }

        if (! $this->isError() && (null === $this->getId())) {
            header('HTTP/1.1 204 No Content');
            return;
        }

        if (null === ($smd = $this->getServiceMap())) {
            return;
        }

        $contentType = $smd->getContentType();
        if (! empty($contentType)) {
            header('Content-Type: ' . $contentType);
        }
    }
}
