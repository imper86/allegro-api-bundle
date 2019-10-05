<?php
/**
 * Author: Adrian Szuszkiewicz <me@imper.info>
 * Github: https://github.com/imper86
 * Date: 04.10.2019
 * Time: 13:27
 */

namespace Imper86\AllegroApiBundle\Manager;


use Imper86\AllegroApiBundle\Entity\AllegroAccountInterface;
use Imper86\AllegroApiBundle\Service\AllegroClient;
use Imper86\AllegroApiBundle\Service\AllegroClientInterface;
use Imper86\AllegroApiBundle\Service\TokenBundleServiceInterface;
use Imper86\AllegroRestApiSdk\AllegroClientInterface as BaseAllegroClientInterface;

/**
 * Class AllegroClientManager
 * @package Imper86\AllegroApiBundle\Manager
 */
class AllegroClientManager implements AllegroClientManagerInterface
{
    /**
     * @var AllegroClientInterface[]
     */
    private $instances = [];
    /**
     * @var array
     */
    private $config;
    /**
     * @var TokenBundleServiceInterface
     */
    private $tokenBundleService;
    /**
     * @var BaseAllegroClientInterface
     */
    private $baseAllegroClient;
    /**
     * @var AllegroAccountManagerInterface
     */
    private $accountManager;

    /**
     * @param array $config
     * @param TokenBundleServiceInterface $tokenBundleService
     * @param AllegroAccountManagerInterface $accountManager
     * @param BaseAllegroClientInterface $baseAllegroClient
     */
    public function __construct(
        array $config,
        TokenBundleServiceInterface $tokenBundleService,
        AllegroAccountManagerInterface $accountManager,
        BaseAllegroClientInterface $baseAllegroClient
    )
    {
        $this->config = $config;
        $this->tokenBundleService = $tokenBundleService;
        $this->baseAllegroClient = $baseAllegroClient;
        $this->accountManager = $accountManager;
    }

    /**
     * @param AllegroAccountInterface|null $account
     * @return AllegroClientInterface
     */
    public function get(?AllegroAccountInterface $account = null): AllegroClientInterface
    {
        if (null === $account) {
            $account = $this->accountManager->get('client');
        }

        $ref = &$this->instances[$account->getId()];

        if (!isset($ref)) {
            $ref = new AllegroClient(
                $account,
                $this->config['client_default_max_retries'] ?? 0,
                $this->tokenBundleService,
                $this->baseAllegroClient
            );
        }

        return $ref;
    }

}
