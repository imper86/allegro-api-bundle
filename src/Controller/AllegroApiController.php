<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 14:18
 */

namespace Imper86\AllegroApiBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Imper86\AllegroApiBundle\Event\AuthCodeEvent;
use Imper86\AllegroRestApiSdk\AllegroAuthInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AllegroApiController extends AbstractController
{
    /**
     * @var AllegroAuthInterface
     */
    private $allegroAuth;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        AllegroAuthInterface $allegroAuth,
        EventDispatcherInterface $eventDispatcher,
        EntityManagerInterface $entityManager
    )
    {
        $this->allegroAuth = $allegroAuth;
        $this->eventDispatcher = $eventDispatcher;
        $this->entityManager = $entityManager;
    }

    public function startAuthorization(): Response
    {
        return new RedirectResponse($this->allegroAuth->createAuthUrl());
    }

    public function handleCode(Request $request): Response
    {
        if ($request->query->get('code')) {
            $event = new AuthCodeEvent($request->query->get('code'));

            $this->eventDispatcher->dispatch($event);

            $this->entityManager->flush();

            return $event->getResponse();
        }

        throw new \Exception('No code in query :-(');
    }
}
