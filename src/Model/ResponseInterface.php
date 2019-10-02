<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 02.10.2019
 * Time: 12:36
 */

namespace Imper86\AllegroApiBundle\Model;


use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

interface ResponseInterface extends BaseResponseInterface
{
    public function getBodyRaw(): ?string;

    public function getBodyDecoded(): ?array;
}
