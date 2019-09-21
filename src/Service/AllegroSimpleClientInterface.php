<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 17:04
 */

namespace Imper86\AllegroApiBundle\Service;


use Psr\Http\Message\RequestInterface;

interface AllegroSimpleClientInterface
{
    public function restRequest(RequestInterface $request): ?array;

    public function soapRequest($requestObject);
}
