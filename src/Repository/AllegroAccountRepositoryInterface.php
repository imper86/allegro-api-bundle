<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:19
 */

namespace Imper86\AllegroApiBundle\Repository;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;

/**
 * Interface AllegroAccountRepositoryInterface
 * @package Imper86\AllegroApiBundle\Repository
 */
interface AllegroAccountRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function findById(string $id): ?AllegroAccountInterface;
}
