<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 14:14
 */

namespace Imper86\AllegroApiBundle\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Imper86\AllegroApiBundle\Entity\AllegroAccount;

/**
 * Class AllegroAccountRepository
 * @package Imper86\AllegroApiBundle\Repository
 *
 * @method AllegroAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroAccount[] findAll()
 * @method AllegroAccount[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroAccountRepository extends ServiceEntityRepository implements AllegroAccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllegroAccount::class);
    }
}
