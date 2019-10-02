<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 17:04
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Model\ResponseInterface;
use Psr\Http\Message\RequestInterface;

interface AllegroSimpleClientInterface
{
    public function restRequest(RequestInterface $request): ResponseInterface;

    public function soapRequest($requestObject);
}
