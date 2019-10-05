<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 14:34
 */

namespace Imper86\AllegroApiBundle\EventSubscriber;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Event\AuthCodeEvent;
use Imper86\AllegroApiBundle\Manager\AllegroAccountManagerInterface;
use Imper86\AllegroApiBundle\Service\UserLoginFetchServiceInterface;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthCodeSubscriber implements EventSubscriberInterface
{
    /**
     * @var AllegroAuthInterface
     */
    private $allegroAuth;
    /**
     * @var AllegroAccountManagerInterface
     */
    private $accountManager;
    /**
     * @var UserLoginFetchServiceInterface
     */
    private $loginFetch;
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
        AllegroAccountManagerInterface $accountManager,
        EntityManagerInterface $entityManager,
        UserLoginFetchServiceInterface $loginFetch
    )
    {
        $this->allegroAuth = $allegroAuth;
        $this->accountManager = $accountManager;
        $this->loginFetch = $loginFetch;
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
            $account = $this->accountManager->get($tokenBundle->getUserId());
            $account->setName($this->loginFetch->fetch($account));
            $account->setAccessToken((string)$tokenBundle->getAccessToken());
            $account->setRefreshToken((string)$tokenBundle->getRefreshToken());
            $account->setGrantType($tokenBundle->getGrantType());

            $this->entityManager->persist($account);
            $this->entityManager->flush();

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
