<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 12:53
 */

namespace Imper86\AllegroApiBundle\Manager;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Service\AllegroClientInterface;

interface AllegroClientManagerInterface
{
    public function get(?AllegroAccountInterface $account = null): AllegroClientInterface;
}
