<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:27
 */

namespace Imper86\AllegroApiBundle\Manager;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Service\AllegroClientInterface;

class AllegroClientManager implements AllegroClientManagerInterface
{
    /**
     * @var AllegroClientInterface[]
     */
    private $instances = [];

    public function get(AllegroAccountInterface $account): AllegroClientInterface
    {
        $ref = &$this->instances[$account->getId()];

//        if (!isset($ref)) {
//            $ref = new AllegroSimpleClient($account, 3)
//        }
//
//        $ref
    }

}
