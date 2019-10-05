<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
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

    public function getRawBody(): ?string
    {
        return (string)$this->getBody();
    }

    public function toArray(): ?array
    {
        return json_decode($this->getRawBody(), true);
    }

}
