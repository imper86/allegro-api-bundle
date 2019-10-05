<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 14:25
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;

/**
 * Interface UserLoginFetchServiceInterface
 * @package Imper86\AllegroApiBundle\Service
 */
interface UserLoginFetchServiceInterface
{
    /**
     * @param AllegroAccountInterface $account
     * @return string
     */
    public function fetch(AllegroAccountInterface $account): string;
}
