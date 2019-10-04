<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 14:50
 */

namespace Imper86\AllegroApiBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Factory\ClientCredentialsAccountFactory;
use Imper86\AllegroApiBundle\Repository\AllegroAccountRepository;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Imper86\AllegroRestApiSdk\Constants\GrantType;
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
    /**
     * @var ClientCredentialsAccountFactory
     */
    private $clientCredentialsAccountFactory;

    public function __construct(
        AllegroAuthInterface $allegroAuth,
        EntityManagerInterface $entityManager,
        AllegroAccountRepository $accountRepository,
        ClientCredentialsAccountFactory $clientCredentialsAccountFactory
    )
    {
        $this->allegroAuth = $allegroAuth;
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
        $this->clientCredentialsAccountFactory = $clientCredentialsAccountFactory;
    }

    public function fetchBundle(AllegroAccount $account, bool $autoRefresh = true): TokenBundleInterface
    {
        $tokenBundle = TokenBundleFactory::buildFromJwtString(
            $account->getAccessToken(),
            $account->getRefreshToken(),
            $account->getGrantType()
        );

        if ($autoRefresh && $tokenBundle->getAccessToken()->isExpired()) {
            return $this->refreshBundle($tokenBundle);
        }

        return $tokenBundle;
    }

    public function refreshBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface
    {
        switch ($tokenBundle->getGrantType()) {
            case GrantType::CLIENT_CREDENTIALS:
                return $this->refreshClientBundle($tokenBundle);
            case GrantType::AUTHORIZATION_CODE:
            case GrantType::REFRESH_TOKEN:
                return $this->refreshUserBundle($tokenBundle);
            default:
                throw new \InvalidArgumentException("Unknown grant type: {$tokenBundle->getGrantType()}");
        }
    }

    public function refreshUserBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface
    {
        if (!in_array($tokenBundle->getGrantType(), [GrantType::AUTHORIZATION_CODE, GrantType::REFRESH_TOKEN])) {
            throw new \InvalidArgumentException("Invalid grant type for method: {$tokenBundle->getGrantType()}");
        }

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

    public function refreshClientBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface
    {
        if (GrantType::CLIENT_CREDENTIALS !== $tokenBundle->getGrantType()) {
            throw new \InvalidArgumentException("Invalid grant type for method: {$tokenBundle->getGrantType()}");
        }

        $account = $this->clientCredentialsAccountFactory->fetchAccount();
        $newBundle = $this->allegroAuth->fetchTokenFromClientCredentials();

        $account->setAccessToken((string)$newBundle->getAccessToken());
        $account->setGrantType($newBundle->getGrantType());

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
