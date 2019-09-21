<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 14:31
 */

namespace Imper86\AllegroApiBundle\Event;


use Imper86\AllegroApiBundle\Entity\AllegroAccount;
use Imper86\AllegroRestApiSdk\Model\Auth\TokenBundleInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class AuthCodeEvent extends Event
{
    /**
     * @var string
     */
    private $authCode;
    /**
     * @var TokenBundleInterface|null
     */
    private $tokenBundle;
    /**
     * @var AllegroAccount|null
     */
    private $allegroAccount;
    /**
     * @var Response|null
     */
    private $response;

    public function __construct(string $authCode)
    {
        $this->authCode = $authCode;
    }

    /**
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @return TokenBundleInterface|null
     */
    public function getTokenBundle(): ?TokenBundleInterface
    {
        return $this->tokenBundle;
    }

    /**
     * @param TokenBundleInterface|null $tokenBundle
     */
    public function setTokenBundle(?TokenBundleInterface $tokenBundle): void
    {
        $this->tokenBundle = $tokenBundle;
    }

    /**
     * @return AllegroAccount|null
     */
    public function getAllegroAccount(): ?AllegroAccount
    {
        return $this->allegroAccount;
    }

    /**
     * @param AllegroAccount|null $allegroAccount
     */
    public function setAllegroAccount(?AllegroAccount $allegroAccount): void
    {
        $this->allegroAccount = $allegroAccount;
    }

    /**
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * @param Response|null $response
     */
    public function setResponse(?Response $response): void
    {
        $this->response = $response;
    }
}
