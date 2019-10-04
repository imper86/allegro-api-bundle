<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:10
 */

namespace Imper86\AllegroApiBundle\Factory;


use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Imper86\AllegroRestApiSdk\AllegroClientInterface;

/**
 * Interface AllegroServiceFactoryInterface
 * @package Imper86\AllegroApiBundle\Factory
 */
interface AllegroServiceFactoryInterface
{
    /**
     * @return AllegroAuthInterface
     */
    public function createAuth(): AllegroAuthInterface;

    /**
     * @return AllegroClientInterface
     */
    public function createClient(): AllegroClientInterface;
}
