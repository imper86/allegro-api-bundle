<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:34
 */

namespace Imper86\AllegroApiBundle\Service;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroRestApiSdk\Model\Auth\TokenBundleInterface;

/**
 * Interface TokenBundleServiceInterface
 * @package Imper86\AllegroApiBundle\Service
 */
interface TokenBundleServiceInterface
{
    /**
     * @param AllegroAccountInterface $account
     * @param bool $autoRefresh
     * @return TokenBundleInterface
     */
    public function fetchBundle(AllegroAccountInterface $account, bool $autoRefresh = true): TokenBundleInterface;

    /**
     * @param TokenBundleInterface $tokenBundle
     * @return TokenBundleInterface
     */
    public function refreshBundle(TokenBundleInterface $tokenBundle): TokenBundleInterface;

    /**
     * @param AllegroAccountInterface $account
     * @return string|null
     */
    public function fetchSoapSessionId(AllegroAccountInterface $account): ?string;
}
