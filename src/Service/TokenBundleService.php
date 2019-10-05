<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 14:50
 */

namespace Imper86\AllegroApiBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Factory\ClientCredentialsAccountFactory;
use Imper86\AllegroApiBundle\Manager\AllegroAccountManagerInterface;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Imper86\AllegroRestApiSdk\Constants\GrantType;
use Imper86\AllegroRestApiSdk\Helper\TokenBundleFactory;
use Imper86\AllegroRestApiSdk\Model\Auth\TokenBundleInterface;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\doLoginWithAccessTokenResponse;

class TokenBundleService implements TokenBundleServiceInterface
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
     * @var AllegroAccountManagerInterface
     */
    private $accountManager;

    public function __construct(
        AllegroAuthInterface $allegroAuth,
        EntityManagerInterface $entityManager,
        AllegroAccountManagerInterface $accountManager
    )
    {
        $this->allegroAuth = $allegroAuth;
        $this->entityManager = $entityManager;
        $this->accountManager = $accountManager;
    }

    public function fetchBundle(AllegroAccountInterface $account, bool $autoRefresh = true): TokenBundleInterface
    {
        if (!$account->getAccessToken()) {
            if (GrantType::CLIENT_CREDENTIALS === $account->getGrantType()) {
                $this->fetchClientCredentialsBundle($account);
            } else {
                throw new \InvalidArgumentException(
                    "Account {$account->getId()} has no access token and is not client_credentials type"
                );
            }
        }

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

    public function fetchSoapSessionId(AllegroAccountInterface $account): ?string
    {
        $sessionId = $this->allegroAuth->fetchSoapSessionId($account->getAccessToken());

        if ($sessionId instanceof doLoginWithAccessTokenResponse) {
            $account->setSoapSessionId($sessionId->getSessionHandlePart());

            $this->entityManager->persist($account);
            $this->entityManager->flush();

            return $account->getSoapSessionId();
        }

        return null;
    }

    private function refreshUserBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface
    {
        if (!in_array($tokenBundle->getGrantType(), [GrantType::AUTHORIZATION_CODE, GrantType::REFRESH_TOKEN])) {
            throw new \InvalidArgumentException("Invalid grant type for method: {$tokenBundle->getGrantType()}");
        }

        $account = $this->accountManager->get($tokenBundle->getUserId());

        if (!$account->getRefreshToken()) {
            throw new \InvalidArgumentException("Account {$account->getId()} has no refresh token");
        }

        $newBundle = $this->allegroAuth->fetchTokenFromRefresh($tokenBundle->getRefreshToken());
        $account->setAccessToken((string)$newBundle->getAccessToken());
        $account->setRefreshToken((string)$newBundle->getRefreshToken());

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $newBundle;
    }

    private function refreshClientBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface
    {
        if (GrantType::CLIENT_CREDENTIALS !== $tokenBundle->getGrantType()) {
            throw new \InvalidArgumentException("Invalid grant type for method: {$tokenBundle->getGrantType()}");
        }

        $account = $this->accountManager->get('client');

        return $this->fetchClientCredentialsBundle($account);
    }

    private function fetchClientCredentialsBundle(AllegroAccountInterface $account): TokenBundleInterface
    {
        if (GrantType::CLIENT_CREDENTIALS !== $account->getGrantType()) {
            throw new \InvalidArgumentException("Invalid grant type for requested method: {$account->getGrantType()}");
        }

        $newBundle = $this->allegroAuth->fetchTokenFromClientCredentials();

        $account->setAccessToken((string)$newBundle->getAccessToken());
        $account->setGrantType($newBundle->getGrantType());

        $this->entityManager->persist($account);
        $this->entityManager->flush();

        return $newBundle;
    }
}
