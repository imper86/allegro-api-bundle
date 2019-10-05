<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:18
 */

namespace Imper86\AllegroApiBundle\Manager;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Factory\AllegroAccountFactoryInterface;
use Imper86\AllegroApiBundle\Repository\AllegroAccountRepositoryInterface;
use Imper86\AllegroRestApiSdk\Constants\GrantType;

/**
 * Class AllegroAccountManager
 * @package Imper86\AllegroApiBundle\Manager
 */
class AllegroAccountManager implements AllegroAccountManagerInterface
{
    /**
     * @var AllegroAccountRepositoryInterface
     */
    private $repository;
    /**
     * @var AllegroAccountFactoryInterface
     */
    private $factory;
    /**
     * @var AllegroAccountInterface[]
     */
    private $accounts = [];

    /**
     * @param AllegroAccountRepositoryInterface $repository
     * @param AllegroAccountFactoryInterface $factory
     */
    public function __construct(AllegroAccountRepositoryInterface $repository, AllegroAccountFactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @param string $accountId
     * @return AllegroAccountInterface|null
     */
    public function get(string $accountId): ?AllegroAccountInterface
    {
        $ref = &$this->accounts[$accountId];

        if (!isset($ref)) {
            $account = $this->repository->findById($accountId);

            $ref = $account ?? $this->factory->generate($accountId, GrantType::CLIENT_CREDENTIALS);
        }

        return $ref;
    }

}
