<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 23.09.2019
 * Time: 16:54
 */

namespace Imper86\AllegroApiBundle\Factory;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Repository\AllegroAccountRepository;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;

class ClientCredentialsAccountFactory
{
    /**
     * @var AllegroAccountRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var AllegroAuthInterface
     */
    private $allegroAuth;

    public function __construct(
        AllegroAuthInterface $allegroAuth,
        AllegroAccountRepository $repository,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->allegroAuth = $allegroAuth;
    }

    public function fetchAccount(): AllegroAccount
    {
        $account = $this->repository->find('client');

        if ($account) {
            return $account;
        }

        $tokenBundle = $this->allegroAuth->fetchTokenFromClientCredentials();

        $account = new AllegroAccount();
        $account->setId('client');
        $account->setAccessToken((string)$tokenBundle->getAccessToken());
        $account->setGrantType($tokenBundle->getGrantType());

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $account;
    }
}
