<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:13
 */

namespace Imper86\AllegroApiBundle\Factory;


use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;

/**
 * Class AllegroAccountFactory
 * @package Imper86\AllegroApiBundle\Factory
 */
class AllegroAccountFactory implements AllegroAccountFactoryInterface
{
    /**
     * @param string $id
     * @param string $grantType
     * @return AllegroAccountInterface
     */
    public function generate(string $id, string $grantType): AllegroAccountInterface
    {
        $allegroAccount = new AllegroAccount($id, $grantType);

        return $allegroAccount;
    }
}
