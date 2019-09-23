<?php
/**
 * Copyright: IMPER.INFO Adrian Szuszkiewicz
 * Date: 21.09.2019
 * Time: 14:05
 */

namespace Imper86\AllegroApiBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class AllegroAccount
 * @package Imper86\AllegroApiBundle\Entity
 *
 * @ORM\Entity(repositoryClass="Imper86\AllegroApiBundle\Repository\AllegroAccountRepository")
 */
class AllegroAccount
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @var string|null
     */
    private $id;
    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     * @var string|null
     */
    private $name;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private $userId;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $accessToken;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $refreshToken;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private $soapSessionId;
    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string|null
     */
    private $grantType;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string|null $userId
     */
    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string|null $accessToken
     */
    public function setAccessToken(?string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param string|null $refreshToken
     */
    public function setRefreshToken(?string $refreshToken): void
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string|null
     */
    public function getSoapSessionId(): ?string
    {
        return $this->soapSessionId;
    }

    /**
     * @param string|null $soapSessionId
     */
    public function setSoapSessionId(?string $soapSessionId): void
    {
        $this->soapSessionId = $soapSessionId;
    }

    /**
     * @return string|null
     */
    public function getGrantType(): ?string
    {
        return $this->grantType;
    }

    /**
     * @param string|null $grantType
     */
    public function setGrantType(?string $grantType): void
    {
        $this->grantType = $grantType;
    }
}
