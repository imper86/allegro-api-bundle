<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:06
 */

namespace Imper86\AllegroApiBundle\Manager;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;

interface AllegroAccountManagerInterface
{
    public function get(string $accountId): ?AllegroAccountInterface;
}
