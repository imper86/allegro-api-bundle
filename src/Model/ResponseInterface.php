<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 02.10.2019
 * Time: 12:36
 */

namespace Imper86\AllegroApiBundle\Model;


use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

interface ResponseInterface extends BaseResponseInterface
{
    public function getRawBody(): ?string;

    public function toArray(): ?array;
}
