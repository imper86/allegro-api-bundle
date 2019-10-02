<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 02.10.2019
 * Time: 12:38
 */

namespace Imper86\AllegroApiBundle\Model;


use GuzzleHttp\Psr7\Response as BaseResponse;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

class Response extends BaseResponse implements ResponseInterface
{
    public function __construct(BaseResponseInterface $response)
    {
        parent::__construct(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    public function getBodyRaw(): ?string
    {
        return (string)$this->getBody();
    }

    public function getBodyDecoded(): ?array
    {
        return json_decode($this->getBodyRaw(), true);
    }

}
