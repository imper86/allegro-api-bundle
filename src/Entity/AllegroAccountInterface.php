<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 12:54
 */

namespace Imper86\AllegroApiBundle\Entity;


/**
 * Interface AllegroAccountInterface
 * @package Imper86\AllegroApiBundle\Entity
 */
interface AllegroAccountInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     */
    public function setId(string $id): void;

    /**
     * @return string
     */
    public function getGrantType(): string;

    /**
     * @param string $grantType
     */
    public function setGrantType(string $grantType): void;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string;

    /**
     * @param string|null $accessToken
     */
    public function setAccessToken(?string $accessToken): void;

    /**
     * @return string|null
     */
    public function getRefreshToken(): ?string;

    /**
     * @param string|null $refreshToken
     */
    public function setRefreshToken(?string $refreshToken): void;

    /**
     * @return string|null
     */
    public function getSoapSessionId(): ?string;

    /**
     * @param string|null $soapSessionId
     */
    public function setSoapSessionId(?string $soapSessionId): void;
}
