<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:08
 */

namespace Imper86\AllegroApiBundle\Factory;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;

interface AllegroAccountFactoryInterface
{
    public function generate(string $id, string $grantType): AllegroAccountInterface;
}
