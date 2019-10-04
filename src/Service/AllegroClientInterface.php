<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 17:04
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Model\ResponseInterface;
use Psr\Http\Message\RequestInterface;

interface AllegroClientInterface
{
    public function restRequest(RequestInterface $request): ResponseInterface;

    public function soapRequest($requestObject);
}
