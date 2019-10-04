<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 21.09.2019
 * Time: 14:05
 */

namespace Imper86\AllegroApiBundle\Entity;


/**
 * Class AllegroAccount
 * @package Imper86\AllegroApiBundle\Entity
 */
class AllegroAccount implements AllegroAccountInterface
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $grantType;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $accessToken;
    /**
     * @var string|null
     */
    private $refreshToken;
    /**
     * @var string|null
     */
    private $soapSessionId;

    public function __construct(string $id, string $grantType)
    {
        $this->id = $id;
        $this->grantType = $grantType;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grantType;
    }

    /**
     * @param string $grantType
     */
    public function setGrantType(string $grantType): void
    {
        $this->grantType = $grantType;
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
}
