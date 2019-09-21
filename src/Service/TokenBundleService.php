<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 14:50
 */

namespace Imper86\AllegroApiBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Repository\AllegroAccountRepository;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Imper86\AllegroRestApiSdk\Helper\TokenBundleFactory;
use Imper86\AllegroRestApiSdk\Model\Auth\TokenBundleInterface;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\doLoginWithAccessTokenResponse;

class TokenBundleService
{
    /**
     * @var AllegroAuthInterface
     */
    private $allegroAuth;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var AllegroAccountRepository
     */
    private $accountRepository;

    public function __construct(
        AllegroAuthInterface $allegroAuth,
        EntityManagerInterface $entityManager,
        AllegroAccountRepository $accountRepository
    )
    {
        $this->allegroAuth = $allegroAuth;
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
    }

    public function fetchBundle(AllegroAccount $account, bool $autoRefresh = true): TokenBundleInterface
    {
        $tokenBundle = TokenBundleFactory::buildFromJwtString(
            $account->getAccessToken(),
            $account->getRefreshToken()
        );

        if ($autoRefresh && $tokenBundle->getAccessToken()->isExpired()) {
            return $this->refreshBundle($tokenBundle);
        }

        return $tokenBundle;
    }

    public function refreshBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface
    {
        $account = $this->accountRepository->find($tokenBundle->getUserId());

        if (!$account) {
            throw new \InvalidArgumentException("Account not found: {$tokenBundle->getUserId()}");
        }

        $newBundle = $this->allegroAuth->fetchTokenFromRefresh($tokenBundle->getRefreshToken());
        $account->setAccessToken((string)$newBundle->getAccessToken());
        $account->setRefreshToken((string)$newBundle->getRefreshToken());

        $this->entityManager->flush();

        return $newBundle;
    }

    public function refreshSessionId(AllegroAccount $account): ?string
    {
        $sessionId = $this->allegroAuth->fetchSoapSessionId($account->getAccessToken());

        if ($sessionId instanceof doLoginWithAccessTokenResponse) {
            $account->setSoapSessionId($sessionId->getSessionHandlePart());
            $this->entityManager->flush();

            return $account->getSoapSessionId();
        }

        return null;
    }
}
