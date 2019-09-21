<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 14:34
 */

namespace Imper86\AllegroApiBundle\EventSubscriber;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroApiBundle\Event\AuthCodeEvent;
use Imper86\AllegroApiBundle\Repository\AllegroAccountRepository;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Imper86\AllegroRestApiSdk\AllegroClientInterface;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\DoGetUserLoginRequest;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\doGetUserLoginResponse;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\DoLoginWithAccessTokenRequest;
use Imper86\AllegroRestApiSdk\Model\SoapWsdl\doLoginWithAccessTokenResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthCodeHandleSubscriber implements EventSubscriberInterface
{
    /**
     * @var AllegroAuthInterface
     */
    private $allegroAuth;
    /**
     * @var AllegroAccountRepository
     */
    private $accountRepository;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var AllegroClientInterface
     */
    private $allegroClient;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public static function getSubscribedEvents()
    {
        return [
            AuthCodeEvent::class => [
                ['generateTokenBundle', 2048],
                ['handleAllegroAccount', 1024],
                ['createErrorResponse', -2048],
            ],
        ];
    }

    public function __construct(
        AllegroAuthInterface $allegroAuth,
        AllegroClientInterface $allegroClient,
        AllegroAccountRepository $accountRepository,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    )
    {
        $this->allegroAuth = $allegroAuth;
        $this->accountRepository = $accountRepository;
        $this->tokenStorage = $tokenStorage;
        $this->allegroClient = $allegroClient;
        $this->entityManager = $entityManager;
    }

    public function generateTokenBundle(AuthCodeEvent $event)
    {
        if (!$event->getTokenBundle()) {
            $tokenBundle = $this->allegroAuth->fetchTokenFromCode($event->getAuthCode());

            $event->setTokenBundle($tokenBundle);
        }
    }

    public function handleAllegroAccount(AuthCodeEvent $event)
    {
        if (!$event->getAllegroAccount() && $tokenBundle = $event->getTokenBundle()) {
            $account = $this->accountRepository->find($tokenBundle->getUserId());

            if (!$account) {
                $account = new AllegroAccount();
                $account->setId($tokenBundle->getUserId());

                $this->entityManager->persist($account);
            }

            $allegroUserLogin = $this->allegroClient->soapRequest(
                new DoGetUserLoginRequest(1, $tokenBundle->getUserId())
            );

            if ($allegroUserLogin instanceof doGetUserLoginResponse) {
                $account->setName($allegroUserLogin->getUserLogin());
            }

            $account->setAccessToken((string)$tokenBundle->getAccessToken());
            $account->setRefreshToken((string)$tokenBundle->getRefreshToken());

            $sessionHandle = $this->allegroClient->soapRequest(
                new DoLoginWithAccessTokenRequest((string)$tokenBundle->getAccessToken(), 1)
            );

            if ($sessionHandle instanceof doLoginWithAccessTokenResponse) {
                $account->setSoapSessionId($sessionHandle->getSessionHandlePart());
            }

            $event->setAllegroAccount($account);
        }
    }

    public function createErrorResponse(AuthCodeEvent $event)
    {
        if (!$event->getResponse()) {
            $event->setResponse(new Response('Not Found', 404));
        }
    }
}
